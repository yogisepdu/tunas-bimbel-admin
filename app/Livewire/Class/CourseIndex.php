<?php

namespace App\Livewire\Class;

use App\Models\ClassRoom;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Support\ClassAccess;

class CourseIndex extends Component
{
    public function render()
    {
        $classes = ClassAccess::classes()
            ->with('teachers.user')
            ->latest()
            ->get();

        return view(
            'livewire.class.course-index',
            compact('classes')
        )->layout('layouts.admin');
    }


    #[On('deleteClass')]
    public function delete(int $id): void
    {
        ClassAccess::ensureManager();

        ClassRoom::findOrFail($id)->delete();

        session()->flash(
            'success',
            'Kelas berhasil dihapus.'
        );

        $this->dispatch('deleted');
    }
}
