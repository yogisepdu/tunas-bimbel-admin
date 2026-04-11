<?php

namespace App\Livewire\SoalSection;

use App\Models\SoalSection;
use App\Models\ClassRoom;
use Livewire\Component;
use Livewire\Attributes\On;

class Index extends Component
{
    public $title;
    public $class_id;

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id'
        ]);

        SoalSection::create([
            'title' => $this->title,
            'class_id' => $this->class_id
        ]);

        $this->reset(['title', 'class_id']);

        session()->flash('success', 'Section berhasil ditambahkan');
    }

    #[On('deleteSection')]
    public function delete($id)
    {
        $section = SoalSection::with('sets.questions.options')->findOrFail($id);

        foreach ($section->sets as $set) {

            foreach ($set->questions as $question) {
                $question->options()->delete();
                $question->delete();
            }

            $set->delete();
        }

        $section->delete();

        session()->flash('success', 'Section & semua data terkait berhasil dihapus');

        $this->dispatch('deleted');
    }

    public function render()
    {
        return view('livewire.soal-section.index', [
            'sections' => SoalSection::with('classRoom')->latest()->get(),
            'classes' => ClassRoom::all()
        ])->layout('layouts.admin');
    }
}