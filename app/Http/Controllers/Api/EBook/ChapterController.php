<?php

namespace App\Http\Controllers\Api\EBook;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Quiz;
use App\Models\UserLearningProgress;
use App\Support\StudentAccess;
use Carbon\Carbon;

class ChapterController extends Controller
{
    public function index()
    {
        $user = auth()->user();

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

        $data = $chapters->map(
            function ($chapter) use (
                $icons,
                $colors
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
                ];
            }
        );

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();

        StudentAccess::ensureStudent(
            $user
        );

        $authorizedChapter =
            StudentAccess::chapter(
                $user,
                (int) $id
            );

        $chapter = Chapter::query()
            ->with([
                'videos:id,chapter_id,title,subtitle,youtube_id,source_type,video_path',
                'materiPdf:id,chapter_id,title,pdf_url,storage_type',
            ])
            ->findOrFail(
                $authorizedChapter->id
            );

        $progress =
            UserLearningProgress::query()
            ->where(
                'user_id',
                $user->id
            )
            ->where(
                'chapter_id',
                $chapter->id
            )
            ->get();

        $items = [];

        foreach (
            $chapter->videos
            as $video
        ) {
            StudentAccess::video(
                $user,
                (int) $video->id
            );

            $isDone = $progress
                ->where(
                    'video_id',
                    $video->id
                )
                ->first();

            $private =
                $video->source_type
                === 'private_file';

            $items[] = [
                'id' =>
                'v-' . $video->id,

                'resourceId' =>
                $video->id,

                'chapterId' =>
                (string)
                $chapter->id,

                'type' =>
                'video',

                'title' =>
                $video->title,

                'subtitle' =>
                $video->subtitle,

                'sourceType' =>
                $video->source_type,

                /*
                 * youtubeId hanya diberikan untuk
                 * source YouTube yang sudah lolos
                 * StudentAccess.
                 */
                'youtubeId' =>
                $private
                    ? null
                    : $video
                    ->youtube_id,

                'videoUrl' =>
                $private
                    ? route(
                        'api.media.video',
                        [
                            'video' =>
                            $video->id,
                        ]
                    )
                    : null,

                'requiresAuth' =>
                $private,

                'isDone' =>
                (bool) $isDone,
            ];
        }

        foreach (
            $chapter->materiPdf
            as $pdf
        ) {
            StudentAccess::pdf(
                $user,
                (int) $pdf->id
            );

            $isDone = $progress
                ->where(
                    'pdf_id',
                    $pdf->id
                )
                ->first();

            $private =
                $pdf->storage_type
                === 'private_file';

            $items[] = [
                'id' =>
                'r-' . $pdf->id,

                'resourceId' =>
                $pdf->id,

                'chapterId' =>
                (string)
                $chapter->id,

                'type' =>
                'rangkuman',

                'title' =>
                $pdf->title,

                'pdfUrl' =>
                $private
                    ? route(
                        'api.media.pdf',
                        [
                            'pdf' =>
                            $pdf->id,
                        ]
                    )
                    : $pdf
                    ->pdf_url,

                'requiresAuth' =>
                $private,

                'isDone' =>
                (bool) $isDone,
            ];
        }

        $quizzes = Quiz::query()
            ->withCount('questions')
            ->where(
                'class_id',
                $chapter->class_id
            )
            ->get();

        foreach (
            $quizzes
            as $quiz
        ) {
            StudentAccess::quiz(
                $user,
                (int) $quiz->id
            );

            $isDone = $progress
                ->where(
                    'quiz_id',
                    $quiz->id
                )
                ->first();

            $items[] = [
                'id' =>
                'q-' . $quiz->id,

                'resourceId' =>
                $quiz->id,

                'chapterId' =>
                (string)
                $chapter->id,

                'type' =>
                'kuis',

                'title' =>
                $quiz->title,

                'totalQuestion' =>
                $quiz
                    ->questions_count,

                'isDone' =>
                (bool) $isDone,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }
}
