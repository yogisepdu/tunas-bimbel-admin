<?php

namespace App\Livewire\Kalender;

use App\Models\CalendarEvent;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Index extends Component
{
    public $currentMonth;
    public $currentYear;
    public $events = [];
    public $holidays = [];
    protected $listeners = ['event-created' => 'loadEvents'];

    public function loadHolidays()
    {
        $response = Http::get("https://libur.deno.dev/api", [
            'year' => $this->currentYear
        ]);

        if ($response->successful()) {

            $this->holidays = collect($response->json())
                ->mapWithKeys(function ($item) {
                    return [
                        $item['date'] => $item['name']
                    ];
                })
                ->toArray();
        }
    }

    public function mount()
    {
        $this->currentYear = now()->year;

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
        $this->events = CalendarEvent::all()->groupBy(function ($event) {
            return Carbon::parse($event->event_date)->format('Y-m-d');
        })->toArray();
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();

        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();

        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function deleteEvent($id)
    {
        CalendarEvent::find($id)?->delete();

        $this->loadEvents();

        session()->flash('message', 'Event berhasil dihapus');
    }

    public function render()
    {
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $daysInMonth = $startOfMonth->daysInMonth;
        $startDay = $startOfMonth->dayOfWeek;

        return view('livewire.kalender.index', [
            'daysInMonth' => $daysInMonth,
            'startDay' => $startDay,
            'startOfMonth' => $startOfMonth,
        ])->layout('layouts.admin');
    }
}
