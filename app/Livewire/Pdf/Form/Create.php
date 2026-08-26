<?php

namespace App\Livewire\Pdf\Form;

use App\Models\MateriPdf;
use App\Support\ClassAccess;
use Livewire\Component;

class Create extends Component
{
    public $class_id;
    public $chapter_id;
    public $title;
    public $pdf_url;

    protected $rules = [
        'class_id' => 'required|integer|exists:classes,id',
        'chapter_id' => 'required|integer|exists:chapters,id',
        'title' => 'required|string|max:255',
        'pdf_url' => 'required|url|max:2048',
    ];

    /**
     * Mengosongkan pilihan sub materi ketika kelas diganti.
     */
    public function updatedClassId()
    {
        $this->reset('chapter_id');
        $this->resetValidation('chapter_id');
    }

    public function save()
    {
        $validated = $this->validate();

        /*
         * Pastikan kelas bisa diakses oleh user.
         * Teacher hanya dapat memilih kelas yang ditugaskan.
         */
        $class = ClassAccess::classOrFail(
            (int) $validated['class_id']
        );

        /*
         * Pastikan chapter juga berasal dari kelas
         * yang dapat diakses oleh user.
         */
        $chapter = ClassAccess::chapterOrFail(
            (int) $validated['chapter_id']
        );

        /*
         * Pastikan chapter benar-benar berada
         * pada kelas yang dipilih.
         */
        if ((int) $chapter->class_id !== (int) $class->id) {
            $this->addError(
                'chapter_id',
                'Sub materi tidak sesuai dengan kelas yang dipilih.'
            );

            return;
        }

        MateriPdf::create([
            'chapter_id' => $chapter->id,
            'title' => $validated['title'],
            'pdf_url' => $validated['pdf_url'],
        ]);

        session()->flash(
            'success',
            'Materi PDF berhasil ditambahkan.'
        );

        return $this->redirect(
            route('pdf.index'),
            navigate: true
        );
    }

    public function render()
    {
        /*
         * Administrator dan admin mendapatkan semua kelas.
         * Teacher hanya mendapatkan kelas yang ditugaskan.
         */
        $classes = ClassAccess::classes()
            ->orderBy('name')
            ->get();

        $chapters = collect();

        /*
         * Ambil chapter berdasarkan kelas yang dipilih.
         */
        if (!empty($this->class_id)) {
            $class = ClassAccess::classOrFail(
                (int) $this->class_id
            );

            $chapters = $class->chapters()
                ->orderBy('title')
                ->get();
        }

        return view('livewire.pdf.form.create', [
            'classes' => $classes,
            'chapters' => $chapters,
        ])->layout('layouts.admin');
    }
}
