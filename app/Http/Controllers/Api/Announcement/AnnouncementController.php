<?php

namespace App\Http\Controllers\Api\Announcement;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    //
     public function index()
    {
        $announcements = Announcement::latest()
            ->take(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'category' => $item->category,
                    'title' => $item->title,
                    'description' => $item->description,
                    'isNew' => $item->is_new,
                    'date' => $item->published_at
                        ? $item->published_at->translatedFormat('l, d M y')
                        : $item->created_at->translatedFormat('l, d M y'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $announcements
        ]);
    }
}
