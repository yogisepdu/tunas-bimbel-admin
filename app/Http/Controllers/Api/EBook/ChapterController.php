<?php

namespace App\Http\Controllers\Api\EBook;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Quiz;
use App\Models\UserLearningProgress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChapterController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $icons = [
            'book','library','calculator','flask','planet',
            'school','reader','create','bulb'
        ];

        $colors = [
            '#F59E0B','#8B5CF6','#22C55E','#3B82F6',
            '#EF4444','#06B6D4','#6366F1'
        ];

        // 🔥 FILTER BERDASARKAN PACKAGE USER
        $chapters = Chapter::whereIn('class_id', function ($query) use ($user) {
            $query->select('class_id')
                ->from('package_classes')
                ->whereIn('package_id', function ($q) use ($user) {
                    $q->select('package_id')
                        ->from('user_packages')
                        ->where('user_id', $user->id);
                });
        })
        ->with([
            'classRoom:id,name',
            'classRoom.quizzes:id,class_id,duration',
            'materiPdf:id,chapter_id,pdf_url'
        ])
        ->select('id','class_id','title','created_at')
        ->latest()
        ->get();

        $data = $chapters->map(function ($chapter) use ($icons, $colors) {

            $pdf = $chapter->materiPdf->first();
            $totalDuration = $chapter->classRoom->quizzes->sum('duration');
            $duration = sprintf('%02d:00', $totalDuration);

            $className = $chapter->classRoom->name;
            $hash = crc32($className);

            return [
                'id' => (string) $chapter->id,
                'title' => $chapter->title,
                'subject' => $className,
                'date' => Carbon::parse($chapter->created_at)->translatedFormat('l, d F Y'),
                'duration' => $duration,
                'icon' => $icons[$hash % count($icons)],
                'color' => $colors[$hash % count($colors)],
                'type' => 'materi',
                'mapel' => $className,
                'pdfUrl' => $pdf ? asset('storage/'.$pdf->pdf_url) : null
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();

        $chapter = Chapter::with([
            'videos:id,chapter_id,title,subtitle,youtube_id',
            'materiPdf:id,chapter_id,title,pdf_url'
        ])->findOrFail($id);

        // 🔥 PROTEKSI AKSES
        $hasAccess = DB::table('user_packages')
            ->join('package_classes', 'user_packages.package_id', '=', 'package_classes.package_id')
            ->where('user_packages.user_id', $user->id)
            ->where('package_classes.class_id', $chapter->class_id)
            ->exists();

        if (!$hasAccess) {
            return response()->json([
                'message' => 'Kamu belum membeli paket ini'
            ], 403);
        }

        $progress = UserLearningProgress::where('user_id', $user->id)
            ->where('chapter_id', $chapter->id)
            ->get();

        $items = [];

        foreach ($chapter->videos as $video) {
            $isDone = $progress->where('video_id', $video->id)->first();

            $items[] = [
                'id' => 'v-'.$video->id,
                'chapterId' => (string) $chapter->id,
                'type' => 'video',
                'title' => $video->title,
                'subtitle' => $video->subtitle,
                'youtubeId' => $video->youtube_id,
                'isDone' => $isDone ? true : false
            ];
        }

        foreach ($chapter->materiPdf as $pdf) {
            $isDone = $progress->where('pdf_id', $pdf->id)->first();

            $items[] = [
                'id' => 'r-'.$pdf->id,
                'chapterId' => (string) $chapter->id,
                'type' => 'rangkuman',
                'title' => $pdf->title,
                'pdfUrl' => $pdf->pdf_url,
                'isDone' => $isDone ? true : false
            ];
        }

        $quizzes = Quiz::withCount('questions')
            ->where('class_id', $chapter->class_id)
            ->get();

        foreach ($quizzes as $quiz) {
            $isDone = $progress->where('quiz_id', $quiz->id)->first();

            $items[] = [
                'id' => 'q-'.$quiz->id,
                'chapterId' => (string) $chapter->id,
                'type' => 'kuis',
                'title' => $quiz->title,
                'totalQuestion' => $quiz->questions_count,
                'isDone' => $isDone ? true : false
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }
}