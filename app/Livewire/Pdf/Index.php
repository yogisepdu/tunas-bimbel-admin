<?php

namespace App\Livewire\Pdf;

use App\Support\ClassAccess;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    #[On('deleteClass')]
    public function delete($id)
    {
        $pdf = ClassAccess::pdfOrFail(
            (int) $id
        );

        $pdf->delete();

        session()->flash(
            'success',
            'Materi PDF berhasil dihapus'
        );

        $this->dispatch('deleted');
    }

    public function render()
    {
        $classes = ClassAccess::classes()
            ->with('chapters.materiPdf')
            ->orderBy('name')
            ->get();

        return view('livewire.pdf.index', [
            'classes' => $classes,
        ])->layout('layouts.admin');
    }
}
