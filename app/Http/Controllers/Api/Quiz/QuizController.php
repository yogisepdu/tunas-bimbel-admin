<?php

namespace App\Http\Controllers\Api\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function questions($chapterId)
    {
        $user = auth()->user();

        // 🔥 1. AMBIL CHAPTER DULU (WAJIB)
        $chapter = Chapter::findOrFail($chapterId);

        // 🔥 2. CEK AKSES
        $hasAccess = DB::table('user_packages')
            ->join('package_classes', 'user_packages.package_id', '=', 'package_classes.package_id')
            ->where('user_packages.user_id', $user->id)
            ->where('package_classes.class_id', $chapter->class_id)
            ->exists();

        if (!$hasAccess) {
            return response()->json([
                'message' => 'Akses ditolak'
            ], 403);
        }

        // 🔥 3. AMBIL QUIZ
        $quiz = Quiz::with(['questions','classRoom'])
            ->where('class_id', $chapter->class_id)
            ->firstOrFail();

        $questions = $quiz->questions->map(function ($q) use ($quiz) {

            return [
                'id' => $q->id,
                'category' => $quiz->classRoom->name,
                'subCategory' => null,
                'text' => $q->question,

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