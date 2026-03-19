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
            'quiz_id' => 'nullable'
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
        ]);

        $result = QuizResult::create([
            'user_id' => $user->id,
            'quiz_id' => $data['quiz_id'],
            'score' => $data['score'],
            'correct' => $data['correct'],
            'wrong' => $data['wrong'],
            'empty' => $data['empty'],
        ]);

        return response()->json([
            'message' => 'Result berhasil disimpan',
            'data' => $result
        ]);
    }
}
