<?php

namespace App\Livewire\Kalender\Form;

use App\Models\CalendarEvent;
use Livewire\Component;

class Index extends Component
{
    public $title;
    public $description;
    public $event_date;

    protected $rules = [
        'title' => 'required|min:3',
        'description' => 'nullable',
        'event_date' => 'required|date'
    ];

    public function save()
    {
        $this->validate();

        CalendarEvent::create([
            'title' => $this->title,
            'description' => $this->description,
            'event_date' => $this->event_date
        ]);

        $this->reset();

        $this->dispatch('event-created');
        session()->flash('success', 'Event berhasil ditambahkan');
        return $this->redirect(route('kalender.index'), navigate:true);
    }

    public function render()
    {
        return view('livewire.kalender.form.index')->layout('layouts.admin');
    }
}
