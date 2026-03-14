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

        $progress = UserLearningProgress::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'chapter_id' => $data['chapter_id']
            ],
            [
                'status' => false,
                'progress_percent' => 0
            ]
        );

        // update kolom yang dikirim
        if (isset($data['video_id'])) {
            $progress->video_id = $data['video_id'];
        }

        if (isset($data['pdf_id'])) {
            $progress->pdf_id = $data['pdf_id'];
        }

        if (isset($data['quiz_id'])) {
            $progress->quiz_id = $data['quiz_id'];
        }

        $progress->status = true;

        $progress->save();

        return response()->json([
            'success' => true,
            'data' => $progress
        ]);
    }
}
