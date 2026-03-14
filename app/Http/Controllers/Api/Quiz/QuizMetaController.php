<?php

namespace App\Http\Controllers\Api\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizMetaController extends Controller
{
    //
    public function show($quizId)
    {
        $quiz = Quiz::find($quizId);

        if (!$quiz) {
            return response()->json([
                'message' => 'Quiz tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'id' => 'quiz-' . $quiz->id, // string id
            'title' => $quiz->title,
            'duration' => $quiz->duration * 60 // convert menit → detik
        ]);
    }
}
