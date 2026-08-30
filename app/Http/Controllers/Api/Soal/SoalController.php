<?php

namespace App\Http\Controllers\Api\Soal;

use App\Http\Controllers\Controller;
use App\Models\SoalResult;
use App\Models\SoalSection;
use App\Models\UserSoalProgress;
use App\Services\AssessmentScoringService;
use App\Support\StudentAccess;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    public function sections()
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
            return response()->json([]);
        }

        $sections = SoalSection::query()
            ->whereIn(
                'class_id',
                $classIds
            )
            ->with('sets')
            ->get();

        return response()->json(
            $sections->map(
                function ($section) {
                    $totalSoal =
                        $section
                        ->sets
                        ->sum(
                            'total_questions'
                        );

                    $icon = match (strtolower(
                        $section->title
                    )) {
                        'tiu' =>
                        'analytics',

                        'twk' =>
                        'book',

                        'tkp' =>
                        'people',

                        default =>
                        'pencil',
                    };

                    $color = match (strtolower(
                        $section->title
                    )) {
                        'tiu' =>
                        '#3B82F6',

                        'twk' =>
                        '#10B981',

                        'tkp' =>
                        '#F59E0B',

                        default =>
                        '#6366F1',
                    };

                    return [
                        'id' =>
                        $section->id,

                        'title' =>
                        $section->title,

                        'total_soal' =>
                        $totalSoal,

                        'date' =>
                        now()
                            ->translatedFormat(
                                'l, d F Y'
                            ),

                        'icon' =>
                        $icon,

                        'color' =>
                        $color,

                        'items' =>
                        $section
                            ->sets
                            ->map(
                                fn($set) => [
                                    'id' =>
                                    $set->id,

                                    'title' =>
                                    $set->title,

                                    'soal' =>
                                    $set
                                        ->total_questions
                                        . ' Soal',

                                    'waktu' =>
                                    $set
                                        ->duration
                                        . ' Menit',

                                    'poin' =>
                                    $set
                                        ->points
                                        . ' Poin',

                                    'badge' =>
                                    $set
                                        ->badge,
                                ]
                            ),
                    ];
                }
            )
        );
    }

    public function sectionsBySet(
        $setId
    ) {
        $user = auth()->user();

        StudentAccess::ensureStudent(
            $user
        );

        $set =
            StudentAccess::soalSet(
                $user,
                (int) $setId
            );

        $set->loadMissing(
            'section'
        );

        return response()->json([
            [
                'id' =>
                $set->section->id,

                'title' =>
                $set->section->title,

                'items' => [
                    [
                        'id' =>
                        $set->id,

                        'title' =>
                        $set->title,

                        'soal' =>
                        $set
                            ->total_questions
                            . ' Soal',

                        'waktu' =>
                        $set
                            ->duration
                            . ' Menit',

                        'poin' =>
                        $set
                            ->points
                            . ' Poin',

                        'badge' =>
                        $set->badge,
                    ],
                ],
            ],
        ]);
    }

    public function questions(
        $setId,
        AssessmentScoringService $scoring
    ) {
        $user = auth()->user();

        StudentAccess::ensureStudent(
            $user
        );

        $set =
            StudentAccess::soalSet(
                $user,
                (int) $setId
            );

        $set->load(
            'questions.options'
        );

        $attempt =
            $scoring->startSoalAttempt(
                $user,
                $set
            );

        return response()->json([
            'set_id' =>
            $set->id,

            'title' =>
            $set->title,

            'duration' =>
            $set->duration,

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

            'questions' =>
            $set
                ->questions
                ->map(
                    function (
                        $question
                    ) {
                        return [
                            'id' =>
                            $question
                                ->id,

                            'text' =>
                            $question
                                ->question,

                            'options' =>
                            $question
                                ->options
                                ->map(
                                    fn($option) => [
                                        'key' =>
                                        $option
                                            ->key,

                                        'text' =>
                                        $option
                                            ->text,
                                    ]
                                ),

                            /*
                                 * TIDAK ADA correctAnswer.
                                 */
                        ];
                    }
                ),
        ]);
    }

    /**
     * Server-side scoring.
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
            $scoring->submitSoal(
                $user,
                $data['attempt_token'],
                $data['answers']
            );

        $set =
            StudentAccess::soalSet(
                $user,
                (int)
                $calculated['set']->id
            );

        return response()->json([
            'message' =>
            'Tryout berhasil dinilai oleh server.',

            'data' => [
                'result_id' =>
                $calculated['result']->id,

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

                'review' =>
                $calculated['review'],
            ],
        ]);
    }

    public function checkSoalProgress(
        $setId
    ) {
        $user = auth()->user();

        StudentAccess::ensureStudent(
            $user
        );

        $set =
            StudentAccess::soalSet(
                $user,
                (int) $setId
            );

        $progress =
            UserSoalProgress::query()
            ->where(
                'user_id',
                $user->id
            )
            ->where(
                'soal_set_id',
                $set->id
            )
            ->first();

        if (! $progress) {
            return response()->json([
                'has_done' => false,
                'result' => null,
            ]);
        }

        $result =
            SoalResult::query()
            ->where(
                'user_id',
                $user->id
            )
            ->where(
                'soal_set_id',
                $set->id
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
                'score' =>
                $result->score,

                'correct' =>
                $result->correct,

                'wrong' =>
                $result->wrong,

                'empty' =>
                $result->empty,

                'soal_set_id' =>
                $result->soal_set_id,

                'answers' =>
                json_decode(
                    $result->answers,
                    true
                ),
            ],
        ]);
    }

    public function leaderboard(
        $soalSetId
    ) {
        $user = auth()->user();

        StudentAccess::ensureStudent(
            $user
        );

        $set =
            StudentAccess::soalSet(
                $user,
                (int) $soalSetId
            );

        $results =
            SoalResult::with('user')
            ->where(
                'soal_set_id',
                $set->id
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
