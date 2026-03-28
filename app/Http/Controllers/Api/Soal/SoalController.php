<?php

namespace App\Http\Controllers\Api\Soal;

use App\Http\Controllers\Controller;
use App\Models\SoalResult;
use App\Models\SoalSection;
use App\Models\SoalSet;
use App\Models\UserSoalProgress;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    //
    // 🔥 SECTION + SET
    public function sections($soalId = null)
    {
        $sections = SoalSection::with('sets')->get();

        return response()->json(
            $sections->map(function ($section) {

                // 🔥 TOTAL SOAL PER SECTION
                $totalSoal = $section->sets->sum('total_questions');

                // 🔥 ICON DINAMIS BERDASARKAN TITLE
                $icon = match (strtolower($section->title)) {
                    'tiu' => 'analytics',
                    'twk' => 'book',
                    'tkp' => 'people',
                    default => 'pencil',
                };

                // 🔥 COLOR DINAMIS
                $color = match (strtolower($section->title)) {
                    'tiu' => '#3B82F6',
                    'twk' => '#10B981',
                    'tkp' => '#F59E0B',
                    default => '#6366F1',
                };

                return [
                    'id' => $section->id,
                    'title' => $section->title,

                    // 🔥 TAMBAHAN
                    'total_soal' => $totalSoal,
                    'date' => now()->translatedFormat('l, d F Y'),
                    'icon' => $icon,
                    'color' => $color,

                    'items' => $section->sets->map(function ($set) {
                        return [
                            'id' => $set->id,
                            'title' => $set->title,
                            'soal' => $set->total_questions . ' Soal',
                            'waktu' => $set->duration . ' Menit',
                            'poin' => $set->points . ' Poin',
                            'badge' => $set->badge,
                        ];
                    }),
                ];
            })
        );
    }

    public function sectionsBySet($setId)
    {
        $sections = SoalSection::with(['sets' => function ($q) use ($setId) {
            $q->where('id', $setId);
        }])->get();

        return response()->json(
            $sections->map(function ($section) {
                return [
                    'id' => $section->id,
                    'title' => $section->title,
                    'items' => $section->sets->map(function ($set) {
                        return [
                            'id' => $set->id,
                            'title' => $set->title,
                            'soal' => $set->total_questions . ' Soal',
                            'waktu' => $set->duration . ' Menit',
                            'poin' => $set->points . ' Poin',
                            'badge' => $set->badge,
                        ];
                    }),
                ];
            })->filter(fn($s) => count($s['items']) > 0)->values()
        );
    }

    // 🔥 QUESTIONS PER SET
    public function questions($setId)
    {
        $set = SoalSet::with('questions.options')->find($setId);

        if (!$set) {
            return response()->json([
                'message' => 'Set tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'set_id' => $set->id,
            'title' => $set->title,
            'duration' => $set->duration, // 🔥 ini penting
            'questions' => $set->questions->map(function ($q) {
                return [
                    'id' => $q->id,
                    'text' => $q->question,
                    'options' => $q->options->map(function ($opt) {
                        return [
                            'key' => $opt->key,
                            'text' => $opt->text,
                        ];
                    }),
                    'correctAnswer' => $q->correct_answer,
                ];
            }),
        ]);
    }

    // Store
    public function storeResult(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'soal_set_id' => 'required|integer',
            'score' => 'required|integer',
            'correct' => 'required|integer',
            'wrong' => 'required|integer',
            'empty' => 'required|integer',
            'answers' => 'nullable|array',
        ]);

        $result = SoalResult::create([
            'user_id' => $user->id,
            'soal_set_id' => $data['soal_set_id'],
            'score' => $data['score'],
            'correct' => $data['correct'],
            'wrong' => $data['wrong'],
            'empty' => $data['empty'],
            'answers' => json_encode($data['answers']),
        ]);

        // 🔥 PROGRESS
        UserSoalProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'soal_set_id' => $data['soal_set_id'],
            ],
            [
                'status' => true
            ]
        );

        return response()->json([
            'message' => 'Soal result saved',
            'data' => $result
        ]);
    }

    public function checkSoalProgress($setId)
    {
        $user = auth()->user();

        $progress = UserSoalProgress::where('user_id', $user->id)
            ->where('soal_set_id', $setId)
            ->first();

        // ❌ BELUM PERNAH KERJAKAN
        if (!$progress) {
            return response()->json([
                'has_done' => false,
                'result' => null
            ]);
        }

        // 🔥 AMBIL RESULT TERAKHIR
        $result = SoalResult::where('user_id', $user->id)
            ->where('soal_set_id', $setId)
            ->latest()
            ->first();

        // ❗ JAGA-JAGA kalau progress ada tapi result kosong
        if (!$result) {
            return response()->json([
                'has_done' => false,
                'result' => null
            ]);
        }

        return response()->json([
            'has_done' => true,
            'result' => [
                'score' => $result->score,
                'correct' => $result->correct,
                'wrong' => $result->wrong,
                'empty' => $result->empty,
                'soal_set_id' => $result->soal_set_id,
                'answers' => json_decode($result->answers, true),
            ]
        ]);
    }

    public function leaderboard($soalSetId)
    {
        $results = SoalResult::with('user')
            ->where('soal_set_id', $soalSetId)
            ->orderByDesc('score')
            ->orderBy('created_at')
            ->get();

        $data = $results->values()->map(function ($item, $index) {
            return [
                'rank' => $index + 1,
                'user_id' => $item->user_id,
                'user_name' => $item->user->name ?? 'User',
                'score' => $item->score,
            ];
        });

        return response()->json($data);
    }
}
