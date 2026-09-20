<?php

namespace App\Http\Controllers\Api\Progress;

use App\Http\Controllers\Controller;
use App\Models\QuizResult;
use App\Models\UserLearningProgress;
use App\Services\AssessmentScoringService;
use App\Support\StudentAccess;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Chapter;
use App\Models\Quiz;

class ProgressController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        StudentAccess::ensureStudent($user);

        $validated = $request->validate([
            'chapter_id' => [
                'required',
                'integer',
                'exists:chapters,id',
            ],

            'video_id' => [
                'nullable',
                'integer',
                'exists:videos,id',
            ],

            'pdf_id' => [
                'nullable',
                'integer',
                'exists:materi_pdfs,id',
            ],

            'quiz_id' => [
                'nullable',
                'integer',
                'exists:quizzes,id',
            ],
        ]);

        /*
    |--------------------------------------------------------------------------
    | Pastikan minimal ada satu resource
    |--------------------------------------------------------------------------
    */

        if (
            empty($validated['video_id']) &&
            empty($validated['pdf_id']) &&
            empty($validated['quiz_id'])
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal satu resource harus dikirim.',
            ], 422);
        }

        /*
    |--------------------------------------------------------------------------
    | Pastikan student memiliki akses ke chapter
    |--------------------------------------------------------------------------
    */

        $chapter = StudentAccess::chapter(
            $user,
            (int) $validated['chapter_id']
        );

        /*
    |--------------------------------------------------------------------------
    | Simpan progress
    |--------------------------------------------------------------------------
    */

        $progress = UserLearningProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'chapter_id' => $chapter->id,
            ],
            [
                'completed_video_ids' => [],
                'completed_pdf_ids' => [],
                'completed_quiz_ids' => [],
                'progress_percent' => 0,
                'status' => false,
            ]
        );

        /*
    |--------------------------------------------------------------------------
    | VIDEO
    |--------------------------------------------------------------------------
    */

        if (!empty($validated['video_id'])) {

            $videoId = (int) $validated['video_id'];

            StudentAccess::video($user, $videoId);

            $completedVideos = array_map(
                'intval',
                $progress->completed_video_ids ?? []
            );

            if (!in_array($videoId, $completedVideos, true)) {
                $completedVideos[] = $videoId;
            }

            $progress->completed_video_ids = array_values(
                array_unique($completedVideos)
            );
        }

        /*
    |--------------------------------------------------------------------------
    | PDF
    |--------------------------------------------------------------------------
    */

        if (!empty($validated['pdf_id'])) {

            $pdfId = (int) $validated['pdf_id'];

            StudentAccess::pdf($user, $pdfId);

            $completedPdfs = array_map(
                'intval',
                $progress->completed_pdf_ids ?? []
            );

            if (!in_array($pdfId, $completedPdfs, true)) {
                $completedPdfs[] = $pdfId;
            }

            $progress->completed_pdf_ids = array_values(
                array_unique($completedPdfs)
            );
        }

        /*
    |--------------------------------------------------------------------------
    | QUIZ
    |--------------------------------------------------------------------------
    */

        if (!empty($validated['quiz_id'])) {

            $quizId = (int) $validated['quiz_id'];

            StudentAccess::quiz($user, $quizId);

            $completedQuizzes = array_map(
                'intval',
                $progress->completed_quiz_ids ?? []
            );

            if (!in_array($quizId, $completedQuizzes, true)) {
                $completedQuizzes[] = $quizId;
            }

            $progress->completed_quiz_ids = array_values(
                array_unique($completedQuizzes)
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Hitung total resource
    |--------------------------------------------------------------------------
    */

        $totalVideos = $chapter->videos()->count();

        $totalPdfs = $chapter->materiPdf()->count();

        $totalQuizzes = Quiz::query()
            ->where('class_id', $chapter->class_id)
            ->count();

        $totalResources =
            $totalVideos +
            $totalPdfs +
            $totalQuizzes;

        /*
    |--------------------------------------------------------------------------
    | Hitung resource yang sudah selesai
    |--------------------------------------------------------------------------
    */

        $completedVideosCount = count(
            $progress->completed_video_ids ?? []
        );

        $completedPdfsCount = count(
            $progress->completed_pdf_ids ?? []
        );

        $completedQuizzesCount = count(
            $progress->completed_quiz_ids ?? []
        );

        $completedResources =
            $completedVideosCount +
            $completedPdfsCount +
            $completedQuizzesCount;

        /*
    |--------------------------------------------------------------------------
    | Hitung persentase
    |--------------------------------------------------------------------------
    */

        if ($totalResources > 0) {
            $progressPercent = (int) round(
                ($completedResources / $totalResources) * 100
            );
        } else {
            $progressPercent = 0;
        }

        $progressPercent = max(
            0,
            min(100, $progressPercent)
        );

        $progress->progress_percent = $progressPercent;

        $progress->status = $progressPercent >= 100;

        /*
    |--------------------------------------------------------------------------
    | Simpan
    |--------------------------------------------------------------------------
    */

        $progress->save();

        /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

        return response()->json([
            'success' => true,
            'message' => 'Progress berhasil disimpan.',

            'data' => [
                'chapterId' => (string) $chapter->id,

                'completedVideoIds' => array_values(
                    array_map(
                        'intval',
                        $progress->completed_video_ids ?? []
                    )
                ),

                'completedPdfIds' => array_values(
                    array_map(
                        'intval',
                        $progress->completed_pdf_ids ?? []
                    )
                ),

                'completedQuizIds' => array_values(
                    array_map(
                        'intval',
                        $progress->completed_quiz_ids ?? []
                    )
                ),

                'progressPercent' => (int) $progress->progress_percent,

                'status' => (bool) $progress->status,
            ],
        ]);
    }

    /**
     * Server-side scoring.
     *
     * Client TIDAK mengirim score/correct/wrong/empty lagi.
     */
    public function storeResult(
        Request $request,
        AssessmentScoringService $scoring
    ) {
        $user = $request->user();

        StudentAccess::ensureStudent(
            $user
        );

        $data = $request->validate([
            'attempt_token' => [
                'required',
                'uuid',
            ],

            'answers' => [
                'present',
                'array',
            ],
        ]);

        $calculated =
            $scoring->submitQuiz(
                $user,
                $data['attempt_token'],
                $data['answers']
            );

        $quiz = StudentAccess::quiz(
            $user,
            (int) $calculated['quiz']->id
        );

        return response()->json([
            'message' =>
            'Quiz berhasil dinilai oleh server.',

            'data' => [
                'result_id' =>
                $calculated['result']->id,

                'quiz_id' =>
                $quiz->id,

                'score' =>
                $calculated['score'],

                'correct' =>
                $calculated['correct'],

                'wrong' =>
                $calculated['wrong'],

                'empty' =>
                $calculated['empty'],

                'review' =>
                $calculated['review'],
            ],
        ]);
    }

    public function checkQuizProgress(
        Request $request,
        $chapterId
    ) {
        $user = $request->user();

        StudentAccess::ensureStudent(
            $user
        );

        $chapter =
            StudentAccess::chapter(
                $user,
                (int) $chapterId
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
            ->whereNotNull(
                'quiz_id'
            )
            ->first();

        if (! $progress) {
            return response()->json([
                'has_done' => false,
                'result' => null,
            ]);
        }

        StudentAccess::quiz(
            $user,
            (int) $progress->quiz_id
        );

        $result = QuizResult::query()
            ->where(
                'user_id',
                $user->id
            )
            ->where(
                'quiz_id',
                $progress->quiz_id
            )
            ->latest()
            ->first();

        if (! $result) {
            return response()->json([
                'has_done' => false,
                'result' => null,
            ]);
        }

        return response()->json([
            'has_done' => true,

            'result' => [
                ...$result->toArray(),

                'answers' =>
                json_decode(
                    $result->answers,
                    true
                ),
            ],
        ]);
    }

    public function leaderboard(
        $quizId
    ) {
        $user = auth()->user();

        StudentAccess::ensureStudent(
            $user
        );

        $quiz = StudentAccess::quiz(
            $user,
            (int) $quizId
        );

        $results =
            QuizResult::with('user')
            ->where(
                'quiz_id',
                $quiz->id
            )
            ->orderByDesc(
                'score'
            )
            ->orderBy(
                'created_at'
            )
            ->get();

        return response()->json(
            $results
                ->values()
                ->map(
                    function (
                        $item,
                        $index
                    ) {
                        return [
                            'rank' =>
                            $index + 1,

                            'user_id' =>
                            $item
                                ->user_id,

                            'user_name' =>
                            $item
                                ->user
                                ?->name
                                ?? 'User',

                            'score' =>
                            $item
                                ->score,
                        ];
                    }
                )
        );
    }
}
