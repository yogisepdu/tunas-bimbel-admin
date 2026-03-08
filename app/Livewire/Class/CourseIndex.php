<?php

namespace App\Livewire\Class;

use App\Models\ClassRoom;
use Livewire\Attributes\On;
use Livewire\Component;

class CourseIndex extends Component
{
    public function render()
    {
        $classes = ClassRoom::latest()->get();

        return view('livewire.class.course-index',[
            'classes'=>$classes]
            )->layout('layouts.admin');
    }


    #[On('deleteClass')]
    public function delete($id)
    {
        ClassRoom::findOrFail($id)->delete();

        session()->flash('success', 'Kelas berhasil dihapus');

        $this->dispatch('deleted');
    }
}
