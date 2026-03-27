<?php

namespace App\Livewire\SoalSection;

use App\Models\SoalSection;
use Livewire\Component;
use Livewire\Attributes\On;

class Index extends Component
{
    public $title;

    public function save()
    {
        $this->validate([
            'title' => 'required'
        ]);

        SoalSection::create([
            'title' => $this->title
        ]);

        $this->reset('title');
        session()->flash('success', 'Section berhasil ditambahkan');
    }

    #[On('deleteClass')]
    public function delete($id)
    {
        $section = SoalSection::with('sets.questions.options')->findOrFail($id);

        foreach ($section->sets as $set) {

            foreach ($set->questions as $question) {
                // hapus options
                $question->options()->delete();

                // hapus question
                $question->delete();
            }

            // hapus set
            $set->delete();
        }

        // hapus section
        $section->delete();
        $this->dispatch('deleted');

        session()->flash('success', 'Section & semua data terkait berhasil dihapus');
    }

    public function render()
    {
        return view('livewire.soal-section.index',[
            'sections' => SoalSection::latest()->get()
        ])->layout('layouts.admin');
    }
}
