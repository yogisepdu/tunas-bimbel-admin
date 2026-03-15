<?php

namespace App\Http\Controllers\Api\Progress;

use App\Http\Controllers\Controller;
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
}
