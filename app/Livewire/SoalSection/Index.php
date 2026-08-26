<?php

namespace App\Livewire\SoalSection;

use App\Models\SoalSection;
use App\Support\ClassAccess;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public $title;
    public $class_id;

    public function save()
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
        ]);

        $class = ClassAccess::classOrFail(
            (int) $validated['class_id']
        );

        SoalSection::create([
            'title' => $validated['title'],
            'class_id' => $class->id,
        ]);

        $this->reset(['title', 'class_id']);

        session()->flash(
            'success',
            'Section berhasil ditambahkan'
        );
    }

    #[On('deleteSection')]
    public function delete($id)
    {
        $section = ClassAccess::sectionOrFail(
            (int) $id
        );

        /*
         * Foreign key cascade akan menghapus:
         * section -> set -> question -> option.
         */
        $section->delete();

        session()->flash(
            'success',
            'Section dan semua data terkait berhasil dihapus'
        );

        $this->dispatch('deleted');
    }

    public function render()
    {
        $classIds = ClassAccess::classIds();

        $sections = SoalSection::query()
            ->whereIn('class_id', $classIds)
            ->with('classRoom')
            ->latest()
            ->get();

        $classes = ClassAccess::classes()
            ->orderBy('name')
            ->get();

        return view('livewire.soal-section.index', [
            'sections' => $sections,
            'classes' => $classes,
        ])->layout('layouts.admin');
    }
}
