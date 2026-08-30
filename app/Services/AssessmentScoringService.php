<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizResult;
use App\Models\SoalAttempt;
use App\Models\SoalResult;
use App\Models\SoalSet;
use App\Models\User;
use App\Models\UserSoalProgress;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AssessmentScoringService
{
    public function startQuizAttempt(
        User $user,
        Quiz $quiz
    ): QuizAttempt {
        $durationMinutes = max(
            1,
            (int) $quiz->duration
        );

        $existing = QuizAttempt::query()
            ->where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where(
                'status',
                QuizAttempt::STATUS_ACTIVE
            )
            ->where(
                'expires_at',
                '>',
                now()
            )
            ->latest('id')
            ->first();

        if ($existing) {
            return $existing;
        }

        QuizAttempt::query()
            ->where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where(
                'status',
                QuizAttempt::STATUS_ACTIVE
            )
            ->where(
                'expires_at',
                '<=',
                now()
            )
            ->update([
                'status' =>
                QuizAttempt::STATUS_EXPIRED,

                'updated_at' =>
                now(),
            ]);

        return QuizAttempt::create([
            'token' =>
            (string) Str::uuid(),

            'user_id' =>
            $user->id,

            'quiz_id' =>
            $quiz->id,

            'status' =>
            QuizAttempt::STATUS_ACTIVE,

            'started_at' =>
            now(),

            'expires_at' =>
            now()->addMinutes(
                $durationMinutes
            ),
        ]);
    }

    public function startSoalAttempt(
        User $user,
        SoalSet $set
    ): SoalAttempt {
        $durationMinutes = max(
            1,
            (int) $set->duration
        );

        $existing = SoalAttempt::query()
            ->where('user_id', $user->id)
            ->where(
                'soal_set_id',
                $set->id
            )
            ->where(
                'status',
                SoalAttempt::STATUS_ACTIVE
            )
            ->where(
                'expires_at',
                '>',
                now()
            )
            ->latest('id')
            ->first();

        if ($existing) {
            return $existing;
        }

        SoalAttempt::query()
            ->where('user_id', $user->id)
            ->where(
                'soal_set_id',
                $set->id
            )
            ->where(
                'status',
                SoalAttempt::STATUS_ACTIVE
            )
            ->where(
                'expires_at',
                '<=',
                now()
            )
            ->update([
                'status' =>
                SoalAttempt::STATUS_EXPIRED,

                'updated_at' =>
                now(),
            ]);

        return SoalAttempt::create([
            'token' =>
            (string) Str::uuid(),

            'user_id' =>
            $user->id,

            'soal_set_id' =>
            $set->id,

            'status' =>
            SoalAttempt::STATUS_ACTIVE,

            'started_at' =>
            now(),

            'expires_at' =>
            now()->addMinutes(
                $durationMinutes
            ),
        ]);
    }

    /**
     * Nilai quiz dan simpan hasil dalam SATU transaksi DB.
     */
    public function submitQuiz(
        User $user,
        string $attemptToken,
        array $answers
    ): array {
        return DB::transaction(
            function () use (
                $user,
                $attemptToken,
                $answers
            ) {
                $attempt = QuizAttempt::query()
                    ->lockForUpdate()
                    ->where(
                        'token',
                        $attemptToken
                    )
                    ->where(
                        'user_id',
                        $user->id
                    )
                    ->firstOrFail();

                $this->assertActiveAttempt(
                    $attempt
                );

                $quiz = Quiz::with(
                    'questions'
                )->findOrFail(
                    $attempt->quiz_id
                );

                $normalized =
                    $this->normalizeAnswers(
                        $answers
                    );

                $calculated =
                    $this->scoreQuizQuestions(
                        $quiz->questions,
                        $normalized
                    );

                $result = QuizResult::create([
                    'user_id' =>
                    $user->id,

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

                    'answers' =>
                    json_encode(
                        $calculated['storedAnswers']
                    ),
                ]);

                $attempt->update([
                    'status' =>
                    QuizAttempt::STATUS_SUBMITTED,

                    'submitted_at' =>
                    now(),
                ]);

                return [
                    'attempt' =>
                    $attempt->fresh(),

                    'quiz' =>
                    $quiz,

                    'result' =>
                    $result,

                    ...$calculated,
                ];
            }
        );
    }

    /**
     * Nilai tryout + simpan hasil + progress dalam SATU transaksi DB.
     */
    public function submitSoal(
        User $user,
        string $attemptToken,
        array $answers
    ): array {
        return DB::transaction(
            function () use (
                $user,
                $attemptToken,
                $answers
            ) {
                $attempt = SoalAttempt::query()
                    ->lockForUpdate()
                    ->where(
                        'token',
                        $attemptToken
                    )
                    ->where(
                        'user_id',
                        $user->id
                    )
                    ->firstOrFail();

                $this->assertActiveAttempt(
                    $attempt
                );

                $set = SoalSet::with(
                    'questions'
                )->findOrFail(
                    $attempt->soal_set_id
                );

                $normalized =
                    $this->normalizeAnswers(
                        $answers
                    );

                $calculated =
                    $this->scoreSoalQuestions(
                        $set,
                        $set->questions,
                        $normalized
                    );

                $result = SoalResult::create([
                    'user_id' =>
                    $user->id,

                    'soal_set_id' =>
                    $set->id,

                    'score' =>
                    $calculated['score'],

                    'correct' =>
                    $calculated['correct'],

                    'wrong' =>
                    $calculated['wrong'],

                    'empty' =>
                    $calculated['empty'],

                    'answers' =>
                    json_encode(
                        $calculated['storedAnswers']
                    ),
                ]);

                UserSoalProgress::updateOrCreate(
                    [
                        'user_id' =>
                        $user->id,

                        'soal_set_id' =>
                        $set->id,
                    ],
                    [
                        'status' =>
                        true,
                    ]
                );

                $attempt->update([
                    'status' =>
                    SoalAttempt::STATUS_SUBMITTED,

                    'submitted_at' =>
                    now(),
                ]);

                return [
                    'attempt' =>
                    $attempt->fresh(),

                    'set' =>
                    $set,

                    'result' =>
                    $result,

                    ...$calculated,
                ];
            }
        );
    }

    private function assertActiveAttempt(
        QuizAttempt|SoalAttempt $attempt
    ): void {
        if (
            $attempt->status !== 'active'
        ) {
            throw ValidationException::withMessages([
                'attempt_token' =>
                'Attempt sudah selesai atau tidak lagi aktif.',
            ]);
        }

        if ($attempt->expires_at->isPast()) {
            $attempt->update([
                'status' => 'expired',
            ]);

            throw ValidationException::withMessages([
                'attempt_token' =>
                'Waktu pengerjaan sudah habis.',
            ]);
        }
    }

    /**
     * Format jawaban yang didukung:
     *
     * {"12":"A","13":"C"}
     *
     * atau:
     *
     * [
     *   {"question_id":12,"answer":"A"},
     *   {"question_id":13,"answer":"C"}
     * ]
     */
    public function normalizeAnswers(
        array $answers
    ): array {
        $normalized = [];

        foreach (
            $answers
            as $key => $value
        ) {
            if (is_array($value)) {
                $questionId =
                    $value['question_id']
                    ?? $value['id']
                    ?? null;

                $answer =
                    $value['answer']
                    ?? $value['selected_answer']
                    ?? $value['selected']
                    ?? null;
            } else {
                $questionId = $key;
                $answer = $value;
            }

            if (
                ! is_numeric(
                    $questionId
                )
            ) {
                continue;
            }

            $answer = strtoupper(
                trim(
                    (string) $answer
                )
            );

            if (
                ! in_array(
                    $answer,
                    ['A', 'B', 'C', 'D'],
                    true
                )
            ) {
                $answer = '';
            }

            $normalized[(int) $questionId] = $answer;
        }

        return $normalized;
    }

    private function scoreQuizQuestions(
        Collection $questions,
        array $answers
    ): array {
        $correct = 0;
        $wrong = 0;
        $empty = 0;
        $review = [];
        $storedAnswers = [];

        foreach ($questions as $question) {
            $selected =
                $answers[$question->id]
                ?? '';

            $answerKey = strtoupper(
                trim(
                    (string)
                    $question->correct_answer
                )
            );

            if ($selected === '') {
                $empty++;
            } elseif (
                hash_equals(
                    $answerKey,
                    $selected
                )
            ) {
                $correct++;
            } else {
                $wrong++;
            }

            $storedAnswers[$question->id] = $selected;

            /*
             * Kunci jawaban hanya muncul sesudah submit.
             */
            $review[] = [
                'question_id' =>
                $question->id,

                'selected_answer' =>
                $selected !== ''
                    ? $selected
                    : null,

                'correct_answer' =>
                $answerKey,

                'is_correct' =>
                $selected !== ''
                    && hash_equals(
                        $answerKey,
                        $selected
                    ),
            ];
        }

        $total = $questions->count();

        $score = $total > 0
            ? (int) round(
                ($correct / $total)
                    * 100
            )
            : 0;

        return compact(
            'score',
            'correct',
            'wrong',
            'empty',
            'storedAnswers',
            'review'
        );
    }

    private function scoreSoalQuestions(
        SoalSet $set,
        Collection $questions,
        array $answers
    ): array {
        $correct = 0;
        $wrong = 0;
        $empty = 0;
        $review = [];
        $storedAnswers = [];

        foreach ($questions as $question) {
            $selected =
                $answers[$question->id]
                ?? '';

            $answerKey = strtoupper(
                trim(
                    (string)
                    $question->correct_answer
                )
            );

            if ($selected === '') {
                $empty++;
            } elseif (
                hash_equals(
                    $answerKey,
                    $selected
                )
            ) {
                $correct++;
            } else {
                $wrong++;
            }

            $storedAnswers[$question->id] = $selected;

            $review[] = [
                'question_id' =>
                $question->id,

                'selected_answer' =>
                $selected !== ''
                    ? $selected
                    : null,

                'correct_answer' =>
                $answerKey,

                'is_correct' =>
                $selected !== ''
                    && hash_equals(
                        $answerKey,
                        $selected
                    ),
            ];
        }

        $total = $questions->count();

        /*
         * points pada SoalSet dipakai sebagai NILAI MAKSIMUM.
         * Jika points <= 0, fallback ke skala 100.
         */
        $maximumScore =
            (int) $set->points;

        if ($maximumScore <= 0) {
            $maximumScore = 100;
        }

        $score = $total > 0
            ? (int) round(
                ($correct / $total)
                    * $maximumScore
            )
            : 0;

        return compact(
            'score',
            'correct',
            'wrong',
            'empty',
            'storedAnswers',
            'review'
        );
    }
}
