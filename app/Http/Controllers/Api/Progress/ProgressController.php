<?php

namespace App\Http\Controllers\Api\Progress;

use App\Http\Controllers\Controller;
use App\Models\QuizResult;
use App\Models\UserChapterProgress;
use App\Models\UserLearningProgress;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    //
    public function store(Request $request)
    {
        $data = $request->validate([
            'chapter_id' => 'required',
            'video_id' => 'nullable',
            'pdf_id' => 'nullable',
            'quiz_id' => 'nullable',
        ]);

        $progress = UserLearningProgress::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'chapter_id' => $data['chapter_id'],
                'video_id' => $data['video_id'] ?? null,
                'pdf_id' => $data['pdf_id'] ?? null,
                'quiz_id' => $data['quiz_id'] ?? null,
            ],
            [
                'status' => true
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $progress
        ]);
    }

    public function storeResult(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'quiz_id' => 'required|integer',
            'score' => 'required|integer',
            'correct' => 'required|integer',
            'wrong' => 'required|integer',
            'empty' => 'required|integer',
            'answers' => 'nullable|array', // 🔥 tambah
        ]);

        $result = QuizResult::create([
            'user_id' => $user->id,
            'quiz_id' => $data['quiz_id'],
            'score' => $data['score'],
            'correct' => $data['correct'],
            'wrong' => $data['wrong'],
            'empty' => $data['empty'],
            'answers' => json_encode($data['answers']), // 🔥 simpan
        ]);

        return response()->json([
            'message' => 'Result berhasil disimpan',
            'data' => $result
        ]);
    }

    public function checkQuizProgress(Request $request, $chapterId)
    {
        $user = $request->user();

        $progress = UserLearningProgress::where('user_id', $user->id)
            ->where('chapter_id', $chapterId)
            ->whereNotNull('quiz_id')
            ->first();

        if (!$progress) {
            return response()->json([
                'has_done' => false
            ]);
        }

        $result = QuizResult::where('user_id', $user->id)
            ->where('quiz_id', $progress->quiz_id)
            ->latest()
            ->first();

        return response()->json([
            'has_done' => true,
            'result' => [
                ...$result->toArray(),
                'answers' => json_decode($result->answers, true), // 🔥 FIX
            ]
        ]);
    }

    // 🔥 fitur baru: leaderboard
    public function leaderboard($quizId)
    {
        $results = QuizResult::with('user')
            ->where('quiz_id', $quizId)
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
