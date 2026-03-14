<?php

namespace App\Http\Controllers\Api\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    //
    public function questions($quizId)
    {
        $quiz = Quiz::with(['questions','classRoom'])->findOrFail($quizId);

        $questions = $quiz->questions->map(function ($q) use ($quiz) {

            return [
                'id' => $q->id,
                'category' => $quiz->classRoom->name, // ambil dari class
                'subCategory' => null,
                'text' => $q->question,
                'options' => [
                    [
                        'key' => 'A',
                        'text' => $q->option_a
                    ],
                    [
                        'key' => 'B',
                        'text' => $q->option_b
                    ],
                    [
                        'key' => 'C',
                        'text' => $q->option_c
                    ],
                    [
                        'key' => 'D',
                        'text' => $q->option_d
                    ]
                ],
                'correctAnswer' => $q->correct_answer
            ];
        });

        return response()->json([
            'quiz_id' => $quiz->id,
            'title' => $quiz->title,
            'category' => $quiz->classRoom->name,
            'duration' => $quiz->duration,
            'questions' => $questions
        ]);
    }
}
