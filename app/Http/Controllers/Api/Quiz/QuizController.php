<?php

namespace App\Http\Controllers\Api\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    //
    public function questions($chapterId)
    {
        // ambil chapter
        $chapter = Chapter::findOrFail($chapterId);

        // cari quiz berdasarkan class yang sama
        $quiz = Quiz::with(['questions','classRoom'])
            ->where('class_id', $chapter->class_id)
            ->firstOrFail();

        $questions = $quiz->questions->map(function ($q) use ($quiz) {

            return [
                'id' => $q->id,
                'category' => $quiz->classRoom->name,
                'subCategory' => null,
                'text' => $q->question,

                // 🔥 TAMBAHKAN IMAGE
                'image' => $q->image ? Storage::url($q->image) : null,

                'options' => [
                    ['key' => 'A', 'text' => $q->option_a],
                    ['key' => 'B', 'text' => $q->option_b],
                    ['key' => 'C', 'text' => $q->option_c],
                    ['key' => 'D', 'text' => $q->option_d],
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
