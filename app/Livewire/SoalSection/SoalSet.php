<?php

namespace App\Livewire\SoalSection;

use App\Models\SoalSection;
use App\Models\SoalSet as SoalSetModel;
use Livewire\Component;

class SoalSet extends Component
{
    public $soal_section_id;
    public $title;
    public $duration;
    public $points;
    public $badge;

    public $badges = [
        ['label' => '🔥 HOTS', 'value' => 'hots'],
        ['label' => '🧠 Easy', 'value' => 'easy'],
        ['label' => '⚡ Medium', 'value' => 'medium'],
        ['label' => '💀 Hard', 'value' => 'hard'],
    ];

    public function save()
    {
        $this->validate([
            'soal_section_id' => 'required',
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'points' => 'nullable|integer|min:0',
            'badge' => 'nullable|in:hots,easy,medium,hard',
        ]);

        // dd(
        //     SoalSetModel::with('section')->first()
        // );

        SoalSetModel::create([
            'soal_section_id' => $this->soal_section_id,
            'title' => $this->title,
            'duration' => $this->duration,
            'points' => $this->points,
            'badge' => $this->badge,
            'total_questions' => 0, // default
        ]);

        $this->reset(['soal_section_id', 'title', 'duration', 'points', 'badge']);

        session()->flash('success', 'Soal Set berhasil ditambahkan');
    }

    public function delete($id)
    {
        $set = SoalSetModel::findOrFail($id);
        $set->delete();

        session()->flash('success', 'Soal Set berhasil dihapus');
    }
    public function render()
    {
        return view('livewire.soal-section.soal-set', [
            'sections' => SoalSection::all(),
            'sets' => SoalSetModel::with('section')->latest()->get()
        ])->layout('layouts.admin');
    }
}
