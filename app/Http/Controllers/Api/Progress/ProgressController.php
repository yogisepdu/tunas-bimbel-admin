<?php

namespace App\Http\Controllers\Api\Progress;

use App\Http\Controllers\Controller;
use App\Models\QuizResult;
use App\Models\UserLearningProgress;
use App\Services\AssessmentScoringService;
use App\Support\StudentAccess;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProgressController extends Controller
{
    public function store(
        Request $request
    ) {
        $user = $request->user();

        StudentAccess::ensureStudent(
            $user
        );

        $data = $request->validate([
            'chapter_id' => [
                'required',
                'integer',
            ],

            'video_id' => [
                'nullable',
                'integer',
            ],

            'pdf_id' => [
                'nullable',
                'integer',
            ],

            'quiz_id' => [
                'nullable',
                'integer',
            ],
        ]);

        $chapter =
            StudentAccess::chapter(
                $user,
                (int) $data['chapter_id']
            );

        if (
            ! empty($data['video_id'])
        ) {
            $video =
                StudentAccess::video(
                    $user,
                    (int)
                    $data['video_id']
                );

            if (
                (int)
                $video->chapter_id
                !==
                (int)
                $chapter->id
            ) {
                throw ValidationException::withMessages([
                    'video_id' =>
                    'Video tidak termasuk dalam chapter ini.',
                ]);
            }
        }

        if (
            ! empty($data['pdf_id'])
        ) {
            $pdf =
                StudentAccess::pdf(
                    $user,
                    (int)
                    $data['pdf_id']
                );

            if (
                (int)
                $pdf->chapter_id
                !==
                (int)
                $chapter->id
            ) {
                throw ValidationException::withMessages([
                    'pdf_id' =>
                    'PDF tidak termasuk dalam chapter ini.',
                ]);
            }
        }

        if (
            ! empty($data['quiz_id'])
        ) {
            $quiz =
                StudentAccess::quiz(
                    $user,
                    (int)
                    $data['quiz_id']
                );

            if (
                (int)
                $quiz->class_id
                !==
                (int)
                $chapter->class_id
            ) {
                throw ValidationException::withMessages([
                    'quiz_id' =>
                    'Quiz tidak termasuk dalam kelas chapter ini.',
                ]);
            }
        }

        $progress =
            UserLearningProgress::firstOrNew([
                'user_id' =>
                $user->id,

                'chapter_id' =>
                $chapter->id,
            ]);

        if (
            ! empty($data['video_id'])
        ) {
            $progress->video_id =
                $data['video_id'];
        }

        if (
            ! empty($data['pdf_id'])
        ) {
            $progress->pdf_id =
                $data['pdf_id'];
        }

        if (
            ! empty($data['quiz_id'])
        ) {
            $progress->quiz_id =
                $data['quiz_id'];
        }

        $done = 0;

        if ($progress->video_id) {
            $done++;
        }

        if ($progress->pdf_id) {
            $done++;
        }

        if ($progress->quiz_id) {
            $done++;
        }

        $progress->progress_percent =
            (int) (
                ($done / 3)
                * 100
            );

        $progress->status =
            $progress->progress_percent
            >= 100;

        $progress->save();

        return response()->json([
            'success' => true,
            'data' => $progress,
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
