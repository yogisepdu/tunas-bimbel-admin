<?php

namespace App\Http\Controllers\Api\Calendar;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CalendarController extends Controller
{
    //
    public function index()
    {
        $year = request('year', now()->year);

        // Event dari database
        $events = CalendarEvent::orderBy('event_date')->get()->map(function ($event) {
            return [
                'date' => $event->event_date,
                'title' => $event->title,
                'description' => $event->description,
                'type' => 'event'
            ];
        });

        // Ambil hari libur nasional
        $holidays = collect();

        $response = Http::get("https://libur.deno.dev/api", [
            'year' => $year
        ]);

        if ($response->successful()) {

            $holidays = collect($response->json())->map(function ($holiday) {

                return [
                    'date' => Carbon::parse($holiday['date'])->format('Y-m-d'),
                    'title' => $holiday['name'],
                    'description' => $holiday['name'],
                    'type' => $holiday['type'] ?? 'libur'
                ];
            });
        }

        // Gabungkan data
        $allEvents = $events
            ->merge($holidays)
            ->sortBy('date')
            ->values();

        return response()->json($allEvents);
    }
}
