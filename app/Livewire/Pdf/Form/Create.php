<?php

namespace App\Livewire\Pdf\Form;

use App\Models\Chapter;
use App\Models\ClassRoom;
use App\Models\MateriPdf;
use Livewire\Component;

class Create extends Component
{
    public $class_id;
    public $chapter_id;
    public $title;
    public $pdf_url;

    protected $rules = [
        'chapter_id' => 'required|exists:chapters,id',
        'title' => 'required|string|max:255',
        'pdf_url' => 'required|url'
    ];

    public function save()
    {
        $this->validate();

        MateriPdf::create([
            'chapter_id' => $this->chapter_id,
            'title' => $this->title,
            'pdf_url' => $this->pdf_url
        ]);

        session()->flash('success', 'Materi PDF berhasil ditambahkan.');

        return $this->redirect(route('pdf.index'), navigate: true);
    }
    public function render()
    {
        return view('livewire.pdf.form.create', [
             'classes' => ClassRoom::all(),
            'chapters' => $this->class_id
                ? Chapter::where('class_id',$this->class_id)->get()
                : []
        ])->layout('layouts.admin');
    }
}
