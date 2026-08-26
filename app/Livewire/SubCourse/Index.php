<?php

namespace App\Livewire\SubCourse;

use App\Support\ClassAccess;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    #[On('deleteClass')]
    public function delete($id)
    {
        $chapter = ClassAccess::chapterOrFail(
            (int) $id
        );

        $chapter->delete();

        session()->flash(
            'success',
            'Chapter berhasil dihapus'
        );

        $this->dispatch('deleted');
    }

    public function render()
    {
        $classes = ClassAccess::classes()
            ->with('chapters')
            ->orderBy('name')
            ->get();

        return view('livewire.sub-course.index', [
            'classes' => $classes,
        ])->layout('layouts.admin');
    }
}
