<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserLearningProgress;

class ActivitiesController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $activities = UserLearningProgress::with('chapter')
            ->where('user_id', $user->id) // ✅ FIX WAJIB
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($item) {

                // 🔥 STATUS
                $status = "BELUM";

                if ($item->progress_percent >= 100) {
                    $status = "SELESAI";
                } elseif ($item->progress_percent > 0) {
                    $status = "LANJUT";
                }

                return [
                    'id' => $item->id,
                    'type' => 'kelas',

                    'title' => $item->chapter->title ?? 'Kelas',

                    'subtitle' => 'Progress: ' . $item->progress_percent . '%',

                    'status' => $status,
                    'progress' => $item->progress_percent,

                    'date' => $item->created_at,
                ];
            });

        return response()->json([
            'message' => 'Aktivitas kelas berhasil diambil',
            'data' => $activities
        ]);
    }
}