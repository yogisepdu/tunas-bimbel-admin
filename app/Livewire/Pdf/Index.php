<?php

namespace App\Livewire\Pdf;

use App\Models\ClassRoom;
use App\Models\MateriPdf;
use Livewire\Component;
use Livewire\Attributes\On;

class Index extends Component
{
    public function render()
    {
        return view('livewire.pdf.index',[
            'classes' => ClassRoom::with([
                'chapters.materiPdf'
            ])->get()
        ])->layout('layouts.admin');
    }

    #[On('deleteClass')]
    public function delete($id)
    {
        MateriPdf::findOrFail($id)->delete();

        session()->flash('success', 'Materi PDF berhasil dihapus');

        $this->dispatch('deleted');
    }
}
