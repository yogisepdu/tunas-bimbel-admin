<?php

namespace App\Http\Controllers\Api\Quiz;

use App\Http\Controllers\Controller;
use App\Support\StudentAccess;

class QuizMetaController extends Controller
{
    public function show($quizId)
    {
        $user = auth()->user();

        StudentAccess::ensureStudent(
            $user
        );

        $quiz = StudentAccess::quiz(
            $user,
            (int) $quizId
        );

        return response()->json([
            'id' =>
            'quiz-' . $quiz->id,

            'title' =>
            $quiz->title,

            /*
             * Menit -> detik
             */
            'duration' =>
            $quiz->duration * 60,
        ]);
    }
}
