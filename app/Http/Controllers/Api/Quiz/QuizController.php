<?php

namespace App\Http\Controllers\Api\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Services\AssessmentScoringService;
use App\Support\StudentAccess;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    public function questions(
        $chapterId,
        AssessmentScoringService $scoring
    ) {
        $user = auth()->user();

        StudentAccess::ensureStudent(
            $user
        );

        $chapter =
            StudentAccess::chapter(
                $user,
                (int) $chapterId
            );

        $quiz = Quiz::query()
            ->with([
                'questions',
                'classRoom',
            ])
            ->where(
                'class_id',
                $chapter->class_id
            )
            ->firstOrFail();

        StudentAccess::quiz(
            $user,
            (int) $quiz->id
        );

        $attempt =
            $scoring->startQuizAttempt(
                $user,
                $quiz
            );

        $questions = $quiz
            ->questions
            ->map(
                function ($question) use (
                    $quiz
                ) {
                    return [
                        'id' =>
                        $question->id,

                        'category' =>
                        $quiz
                            ->classRoom
                            ?->name,

                        'subCategory' =>
                        null,

                        'text' =>
                        $question->question,

                        'image' =>
                        $question->image
                            ? Storage::url(
                                $question->image
                            )
                            : null,

                        'options' => [
                            [
                                'key' => 'A',
                                'text' =>
                                $question
                                    ->option_a,
                            ],
                            [
                                'key' => 'B',
                                'text' =>
                                $question
                                    ->option_b,
                            ],
                            [
                                'key' => 'C',
                                'text' =>
                                $question
                                    ->option_c,
                            ],
                            [
                                'key' => 'D',
                                'text' =>
                                $question
                                    ->option_d,
                            ],
                        ],

                        /*
                         * TIDAK ADA correctAnswer.
                         */
                    ];
                }
            );

        return response()->json([
            'quiz_id' =>
            $quiz->id,

            'attempt_token' =>
            $attempt->token,

            'started_at' =>
            $attempt
                ->started_at
                ->toIso8601String(),

            'expires_at' =>
            $attempt
                ->expires_at
                ->toIso8601String(),

            'duration' =>
            $quiz->duration,

            'title' =>
            $quiz->title,

            'category' =>
            $quiz
                ->classRoom
                ?->name,

            'questions' =>
            $questions,
        ]);
    }
}
