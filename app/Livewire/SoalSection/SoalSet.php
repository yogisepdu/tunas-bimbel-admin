<?php

namespace App\Livewire\SoalSection;

use App\Models\SoalSet as SoalSetModel;
use App\Support\ClassAccess;
use Livewire\Component;

class SoalSet extends Component
{
    public $soal_section_id;
    public $title;
    public $duration;
    public $points;
    public $badge;

    /*
     * Dibutuhkan karena field ini masih terdapat
     * pada Blade soal-set.
     */
    public $total_questions = 0;

    public $badges = [
        ['label' => '🔥 HOTS', 'value' => 'hots'],
        ['label' => '🧠 Easy', 'value' => 'easy'],
        ['label' => '⚡ Medium', 'value' => 'medium'],
        ['label' => '💀 Hard', 'value' => 'hard'],
    ];

    public function save()
    {
        $validated = $this->validate([
            'soal_section_id' => 'required|integer|exists:soal_sections,id',
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'points' => 'nullable|integer|min:0',
            'badge' => 'nullable|in:hots,easy,medium,hard',
        ]);

        $section = ClassAccess::sectionOrFail(
            (int) $validated['soal_section_id']
        );

        SoalSetModel::create([
            'soal_section_id' => $section->id,
            'title' => $validated['title'],
            'duration' => $validated['duration'],
            'points' => $validated['points'] ?? 0,
            'badge' => $validated['badge'] ?? null,
            'total_questions' => 0,
        ]);

        $this->reset([
            'soal_section_id',
            'title',
            'duration',
            'points',
            'badge',
        ]);

        session()->flash(
            'success',
            'Soal Set berhasil ditambahkan'
        );
    }

    public function delete($id)
    {
        $set = ClassAccess::setOrFail(
            (int) $id
        );

        $set->delete();

        session()->flash(
            'success',
            'Soal Set berhasil dihapus'
        );
    }

    public function render()
    {
        $classIds = ClassAccess::classIds();

        $sections = \App\Models\SoalSection::query()
            ->whereIn('class_id', $classIds)
            ->with('classRoom')
            ->orderBy('title')
            ->get();

        $sets = SoalSetModel::query()
            ->whereHas('section', function ($query) use ($classIds) {
                $query->whereIn('class_id', $classIds);
            })
            ->with('section.classRoom')
            ->latest()
            ->get();

        return view('livewire.soal-section.soal-set', [
            'sections' => $sections,
            'sets' => $sets,
        ])->layout('layouts.admin');
    }
}
