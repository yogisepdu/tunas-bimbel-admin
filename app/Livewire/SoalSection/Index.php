<?php

namespace App\Livewire\SoalSection;

use App\Models\SoalSection;
use Livewire\Component;

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
    public function render()
    {
        return view('livewire.soal-section.index',[
            'sections' => SoalSection::latest()->get()
        ])->layout('layouts.admin');
    }
}
