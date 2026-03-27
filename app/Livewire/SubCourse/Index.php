<?php

namespace App\Livewire\SubCourse;

use App\Models\Chapter;
use App\Models\ClassRoom;
use Livewire\Component;
use Livewire\Attributes\On;

class Index extends Component
{
    public function render()
    {
        
        return view('livewire.sub-course.index',[
            'classes' => ClassRoom::with('chapters')->get()
        ])->layout('layouts.admin');
    }


    #[On('deleteClass')]
    public function delete($id)
    {
        Chapter::findOrFail($id)->delete();

        session()->flash('success', 'Chapter berhasil dihapus');

        $this->dispatch('deleted');
    }
}
