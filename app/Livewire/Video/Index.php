<?php

namespace App\Livewire\Video;

use App\Models\ClassRoom;
use App\Models\Video;
use Livewire\Component;
use Livewire\Attributes\On;

class Index extends Component
{
    #[On('deleteClass')]
    public function delete($id)
    {
        Video::findOrFail($id)->delete();

        session()->flash('message', 'Video berhasil dihapus');
        $this->dispatch('deleted');
    }

    public function render()
    {
        return view('livewire.video.index',[
            'classes' => ClassRoom::with([
            'chapters.videos'
            ])->get()
        ])->layout('layouts.admin');
    }
}
