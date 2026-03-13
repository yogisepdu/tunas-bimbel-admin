<?php

namespace App\Livewire\Pdf\Form;

use App\Models\Chapter;
use App\Models\ClassRoom;
use App\Models\MateriPdf;
use Livewire\Component;

class Edit extends Component
{
    public $pdfId;

    public $class_id;
    public $chapter_id;
    public $title;
    public $pdf_url;

    public function mount($id)
    {
        $pdf = MateriPdf::findOrFail($id);

        $this->pdfId = $pdf->id;
        $this->chapter_id = $pdf->chapter_id;
        $this->title = $pdf->title;
        $this->pdf_url = $pdf->pdf_url;

        // ambil class dari chapter
        $this->class_id = $pdf->chapter->class_id;
    }

    protected $rules = [
        'chapter_id' => 'required|exists:chapters,id',
        'title' => 'required|string|max:255',
        'pdf_url' => 'required|url'
    ];

    public function update()
    {
        $this->validate();

        $pdf = MateriPdf::findOrFail($this->pdfId);

        $pdf->update([
            'chapter_id' => $this->chapter_id,
            'title' => $this->title,
            'pdf_url' => $this->pdf_url
        ]);

        session()->flash('success', 'Materi berhasil diupdate');

        return redirect()->route('pdf.index');
    }

    public function render()
    {
        return view('livewire.pdf.form.edit', [
            'classes' => ClassRoom::all(),
            'chapters' => $this->class_id
                ? Chapter::where('class_id', $this->class_id)->get()
                : []
        ])->layout('layouts.admin');
    }
}
