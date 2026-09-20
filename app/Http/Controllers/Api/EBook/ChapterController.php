<?php

namespace App\Http\Controllers\Api\EBook;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Quiz;
use App\Models\UserLearningProgress;
use App\Support\StudentAccess;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    /**
     * Menampilkan daftar chapter yang dapat diakses oleh student.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        StudentAccess::ensureStudent(
            $user
        );

        $classIds =
            StudentAccess::accessibleClassIds(
                $user
            );

        if ($classIds->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }

        $icons = [
            'book',
            'library',
            'calculator',
            'flask',
            'planet',
            'school',
            'reader',
            'create',
            'bulb',
        ];

        $colors = [
            '#F59E0B',
            '#8B5CF6',
            '#22C55E',
            '#3B82F6',
            '#EF4444',
            '#06B6D4',
            '#6366F1',
        ];

        $chapters = Chapter::query()
            ->whereIn(
                'class_id',
                $classIds
            )
            ->with([
                'classRoom:id,name',
                'classRoom.quizzes:id,class_id,duration',
                'materiPdf:id,chapter_id,pdf_url,storage_type',
            ])
            ->select([
                'id',
                'class_id',
                'title',
                'created_at',
            ])
            ->latest()
            ->get();

        /*
         * Ambil progress seluruh chapter milik user
         * sekaligus agar tidak melakukan query berulang.
         */
        $chapterProgress = UserLearningProgress::query()
            ->where(
                'user_id',
                $user->id
            )
            ->whereIn(
                'chapter_id',
                $chapters->pluck('id')
            )
            ->get()
            ->groupBy('chapter_id');

        $data = $chapters->map(
            function ($chapter) use (
                $icons,
                $colors,
                $chapterProgress
            ) {
                $pdf =
                    $chapter
                    ->materiPdf
                    ->first();

                $totalDuration =
                    $chapter
                    ->classRoom
                    ?->quizzes
                    ?->sum('duration')
                    ?? 0;

                $className =
                    $chapter
                    ->classRoom
                    ?->name
                    ?? 'Kelas';

                $hash = crc32(
                    $className
                );

                $pdfUrl = null;

                if ($pdf) {
                    if (
                        $pdf->storage_type
                        === 'private_file'
                    ) {
                        $pdfUrl = route(
                            'api.media.pdf',
                            [
                                'pdf' =>
                                $pdf->id,
                            ]
                        );
                    } else {
                        $pdfUrl =
                            $pdf->pdf_url;
                    }
                }

                /*
                 * Ambil progress chapter user.
                 */
                $progressRows =
                    $chapterProgress->get(
                        $chapter->id,
                        collect()
                    );

                /*
                 * Hitung resource yang sudah selesai.
                 *
                 * Kita menggunakan collection agar tetap aman
                 * apabila database saat ini masih memiliki
                 * lebih dari satu row progress untuk chapter yang sama.
                 */
                $hasVideo =
                    $progressRows->contains(
                        fn($item) =>
                        !is_null($item->video_id)
                    );

                $hasPdf =
                    $progressRows->contains(
                        fn($item) =>
                        !is_null($item->pdf_id)
                    );

                $hasQuiz =
                    $progressRows->contains(
                        fn($item) =>
                        !is_null($item->quiz_id)
                    );

                $doneCount =
                    (int) $hasVideo +
                    (int) $hasPdf +
                    (int) $hasQuiz;

                $progressPercent =
                    (int) round(
                        ($doneCount / 3) * 100
                    );

                $completed =
                    $progressPercent >= 100;

                return [
                    'id' =>
                    (string)
                    $chapter->id,

                    'title' =>
                    $chapter->title,

                    'subject' =>
                    $className,

                    'date' =>
                    Carbon::parse(
                        $chapter
                            ->created_at
                    )->translatedFormat(
                        'l, d F Y'
                    ),

                    'duration' =>
                    sprintf(
                        '%02d:00',
                        $totalDuration
                    ),

                    'icon' =>
                    $icons[$hash
                        % count($icons)],

                    'color' =>
                    $colors[$hash
                        % count($colors)],

                    'type' =>
                    'materi',

                    'mapel' =>
                    $className,

                    'pdfUrl' =>
                    $pdfUrl,

                    'pdfRequiresAuth' =>
                    $pdf
                        && $pdf->storage_type
                        === 'private_file',

                    /*
                     * Progress chapter.
                     */
                    'progress' => [
                        'percent' =>
                        $progressPercent,

                        'completed' =>
                        $completed,
                    ],
                ];
            }
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Menampilkan detail chapter:
     * - Video
     * - PDF
     * - Quiz
     * - Status progress masing-masing resource
     * - Progress keseluruhan chapter
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        StudentAccess::ensureStudent($user);

        /*
    |--------------------------------------------------------------------------
    | Pastikan chapter boleh diakses student
    |--------------------------------------------------------------------------
    */

        $authorizedChapter = StudentAccess::chapter(
            $user,
            (int) $id
        );

        /*
    |--------------------------------------------------------------------------
    | Ambil chapter
    |--------------------------------------------------------------------------
    */

        $chapter = Chapter::query()
            ->with([
                'videos:id,chapter_id,title,subtitle,youtube_id,source_type,video_path',

                'materiPdf:id,chapter_id,title,pdf_url,storage_type',
            ])
            ->findOrFail($authorizedChapter->id);

        /*
    |--------------------------------------------------------------------------
    | Ambil SATU progress untuk user + chapter
    |--------------------------------------------------------------------------
    */

        $progress = UserLearningProgress::query()
            ->where('user_id', $user->id)
            ->where('chapter_id', $chapter->id)
            ->first();

        /*
    |--------------------------------------------------------------------------
    | Default progress
    |--------------------------------------------------------------------------
    */

        $completedVideoIds = array_map(
            'intval',
            $progress?->completed_video_ids ?? []
        );

        $completedPdfIds = array_map(
            'intval',
            $progress?->completed_pdf_ids ?? []
        );

        $completedQuizIds = array_map(
            'intval',
            $progress?->completed_quiz_ids ?? []
        );

        /*
    |--------------------------------------------------------------------------
    | Items
    |--------------------------------------------------------------------------
    */

        $items = [];

        /*
    |--------------------------------------------------------------------------
    | VIDEO
    |--------------------------------------------------------------------------
    */

        foreach ($chapter->videos as $video) {

            StudentAccess::video(
                $user,
                (int) $video->id
            );

            $private = $video->source_type === 'private_file';

            $isDone = in_array(
                (int) $video->id,
                $completedVideoIds,
                true
            );

            $items[] = [
                'id' => 'v-' . $video->id,

                'resourceId' => (int) $video->id,

                'chapterId' => (string) $chapter->id,

                'type' => 'video',

                'title' => $video->title,

                'subtitle' => $video->subtitle,

                'sourceType' => $video->source_type,

                'youtubeId' => $private
                    ? null
                    : $video->youtube_id,

                'videoUrl' => $private
                    ? route('api.media.video', [
                        'video' => $video->id,
                    ])
                    : null,

                'requiresAuth' => $private,

                'isDone' => $isDone,
            ];
        }

        /*
    |--------------------------------------------------------------------------
    | PDF / RANGKUMAN
    |--------------------------------------------------------------------------
    */

        foreach ($chapter->materiPdf as $pdf) {

            StudentAccess::pdf(
                $user,
                (int) $pdf->id
            );

            $private = $pdf->storage_type === 'private_file';

            $isDone = in_array(
                (int) $pdf->id,
                $completedPdfIds,
                true
            );

            $items[] = [
                'id' => 'r-' . $pdf->id,

                'resourceId' => (int) $pdf->id,

                'chapterId' => (string) $chapter->id,

                'type' => 'rangkuman',

                'title' => $pdf->title,

                'pdfUrl' => $private
                    ? route('api.media.pdf', [
                        'pdf' => $pdf->id,
                    ])
                    : $pdf->pdf_url,

                'requiresAuth' => $private,

                'isDone' => $isDone,
            ];
        }

        /*
    |--------------------------------------------------------------------------
    | QUIZ
    |--------------------------------------------------------------------------
    */

        $quizzes = Quiz::query()
            ->withCount('questions')
            ->where('class_id', $chapter->class_id)
            ->get();

        foreach ($quizzes as $quiz) {

            StudentAccess::quiz(
                $user,
                (int) $quiz->id
            );

            $isDone = in_array(
                (int) $quiz->id,
                $completedQuizIds,
                true
            );

            $items[] = [
                'id' => 'q-' . $quiz->id,

                'resourceId' => (int) $quiz->id,

                'chapterId' => (string) $chapter->id,

                'type' => 'kuis',

                'title' => $quiz->title,

                'totalQuestion' => $quiz->questions_count,

                'isDone' => $isDone,
            ];
        }

        /*
    |--------------------------------------------------------------------------
    | Hitung total resource
    |--------------------------------------------------------------------------
    */

        $totalResources = count($items);

        $completedResources =
            count($completedVideoIds) +
            count($completedPdfIds) +
            count($completedQuizIds);

        /*
    |--------------------------------------------------------------------------
    | Progress
    |--------------------------------------------------------------------------
    */

        $progressPercent = $progress?->progress_percent ?? 0;

        /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

        return response()->json([
            'success' => true,

            'data' => $items,

            'progress' => [
                'percent' => (int) $progressPercent,

                'status' => (bool) ($progress?->status ?? false),

                'totalResources' => $totalResources,

                'completedResources' => $completedResources,

                'completedVideoIds' => $completedVideoIds,

                'completedPdfIds' => $completedPdfIds,

                'completedQuizIds' => $completedQuizIds,
            ],
        ]);
    }
}
