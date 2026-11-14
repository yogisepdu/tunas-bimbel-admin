<?php

namespace App\Livewire\Kalender;

use App\Models\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Livewire\Component;

class Index extends Component
{
    public $currentMonth;
    public $currentYear;
    public $events = [];
    public $holidays = [];

    protected $listeners = [
        'event-created' => 'loadEvents',
    ];

    /**
     * Mengambil data hari libur dari API.
     * Jika API gagal, gunakan data cache.
     */
    public function loadHolidays()
    {
        $year = (int) $this->currentYear;
        $cacheKey = "calendar_holidays_{$year}";

        try {
            $response = Http::timeout(5)
                ->retry(2, 300)
                ->get('https://libur.deno.dev/api', [
                    'year' => $year,
                ]);

            $response->throw();

            $data = $response->json();

            if (!is_array($data)) {
                throw new \UnexpectedValueException(
                    'Format data hari libur dari API tidak valid.'
                );
            }

            $holidays = collect($data)
                ->filter(function ($item) {
                    return is_array($item)
                        && !empty($item['date'])
                        && isset($item['name']);
                })
                ->mapWithKeys(function ($item) {
                    return [
                        $item['date'] => $item['name'],
                    ];
                })
                ->toArray();

            // Simpan data berhasil selama 7 hari.
            Cache::put(
                $cacheKey,
                $holidays,
                now()->addDays(7)
            );

            $this->holidays = $holidays;
        } catch (
            ConnectionException |
            RequestException |
            \UnexpectedValueException $e
        ) {
            Log::warning('Gagal mengambil data hari libur.', [
                'year' => $year,
                'error' => $e->getMessage(),
            ]);

            // Gunakan cache jika API tidak dapat diakses.
            $this->holidays = Cache::get($cacheKey, []);
        }
    }

    public function mount()
    {
        $this->currentYear = now()->year;
        $this->currentMonth = now()->month;

        $this->loadEvents();
        $this->loadHolidays();
    }

    public function previousYear()
    {
        $this->currentYear--;

        $this->loadHolidays();
    }

    public function nextYear()
    {
        $this->currentYear++;

        $this->loadHolidays();
    }

    public function loadEvents()
    {
        $this->events = CalendarEvent::all()
            ->groupBy(function ($event) {
                return Carbon::parse($event->event_date)
                    ->format('Y-m-d');
            })
            ->toArray();
    }

    public function previousMonth()
    {
        $oldYear = $this->currentYear;

        $date = Carbon::create(
            $this->currentYear,
            $this->currentMonth,
            1
        )->subMonth();

        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;

        if ($oldYear !== $this->currentYear) {
            $this->loadHolidays();
        }
    }

    public function nextMonth()
    {
        $oldYear = $this->currentYear;

        $date = Carbon::create(
            $this->currentYear,
            $this->currentMonth,
            1
        )->addMonth();

        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;

        if ($oldYear !== $this->currentYear) {
            $this->loadHolidays();
        }
    }

    public function deleteEvent($id)
    {
        CalendarEvent::find($id)?->delete();

        $this->loadEvents();

        session()->flash(
            'message',
            'Event berhasil dihapus'
        );
    }

    public function render()
    {
        $startOfMonth = Carbon::create(
            $this->currentYear,
            $this->currentMonth,
            1
        );

        $daysInMonth = $startOfMonth->daysInMonth;
        $startDay = $startOfMonth->dayOfWeek;

        return view('livewire.kalender.index', [
            'daysInMonth' => $daysInMonth,
            'startDay' => $startDay,
            'startOfMonth' => $startOfMonth,
        ])->layout('layouts.admin');
    }
}
