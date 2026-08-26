<?php

namespace App\Livewire\Video;

use App\Support\ClassAccess;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    #[On('deleteClass')]
    public function delete($id)
    {
        $video = ClassAccess::videoOrFail(
            (int) $id
        );

        $video->delete();

        session()->flash(
            'message',
            'Video berhasil dihapus'
        );

        $this->dispatch('deleted');
    }

    public function render()
    {
        $classes = ClassAccess::classes()
            ->with('chapters.videos')
            ->orderBy('name')
            ->get();

        return view('livewire.video.index', [
            'classes' => $classes,
        ])->layout('layouts.admin');
    }
}
