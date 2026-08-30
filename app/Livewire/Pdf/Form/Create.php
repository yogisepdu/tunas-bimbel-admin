<?php

namespace App\Livewire\Pdf\Form;

use App\Models\MateriPdf;
use App\Support\ClassAccess;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $class_id;
    public $chapter_id;
    public $title;

    public string $storage_type = 'private_file';

    public $pdf_file = null;

    public string $pdf_url = '';

    public function updatedClassId(): void
    {
        $this->reset('chapter_id');
        $this->resetValidation(
            'chapter_id'
        );
    }

    public function updatedStorageType(): void
    {
        $this->reset([
            'pdf_file',
            'pdf_url',
        ]);

        $this->resetValidation();
    }

    protected function rules(): array
    {
        $rules = [
            'class_id' => [
                'required',
                'integer',
                'exists:classes,id',
            ],

            'chapter_id' => [
                'required',
                'integer',
                'exists:chapters,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'storage_type' => [
                'required',
                'in:private_file,external_url',
            ],
        ];

        if (
            $this->storage_type
            === 'private_file'
        ) {
            $rules['pdf_file'] = [
                'required',
                'file',
                'mimes:pdf',
                'max:20480',
            ];
        } else {
            $rules['pdf_url'] = [
                'required',
                'url',
                'max:2048',
            ];
        }

        return $rules;
    }

    public function save()
    {
        $validated =
            $this->validate();

        $class =
            ClassAccess::classOrFail(
                (int)
                $validated['class_id']
            );

        $chapter =
            ClassAccess::chapterOrFail(
                (int)
                $validated['chapter_id']
            );

        if (
            (int) $chapter->class_id
            !==
            (int) $class->id
        ) {
            $this->addError(
                'chapter_id',
                'Sub materi tidak sesuai dengan kelas yang dipilih.'
            );

            return null;
        }

        if (
            $this->storage_type
            === 'private_file'
        ) {
            $path =
                $this->pdf_file
                ->store(
                    'learning/pdfs/'
                        . $chapter->id,
                    'local'
                );

            try {
                MateriPdf::create([
                    'chapter_id' =>
                    $chapter->id,

                    'title' =>
                    trim(
                        $validated['title']
                    ),

                    /*
                     * Untuk file private,
                     * pdf_url menyimpan PATH internal.
                     */
                    'pdf_url' =>
                    $path,

                    'storage_type' =>
                    'private_file',

                    'file_mime_type' =>
                    $this
                        ->pdf_file
                        ->getMimeType(),

                    'file_size' =>
                    $this
                        ->pdf_file
                        ->getSize(),
                ]);
            } catch (\Throwable $e) {
                if (
                    Storage::disk('local')
                    ->exists($path)
                ) {
                    Storage::disk('local')
                        ->delete($path);
                }

                throw $e;
            }
        } else {
            MateriPdf::create([
                'chapter_id' =>
                $chapter->id,

                'title' =>
                trim(
                    $validated['title']
                ),

                'pdf_url' =>
                trim(
                    $validated['pdf_url']
                ),

                'storage_type' =>
                'external_url',

                'file_mime_type' =>
                null,

                'file_size' =>
                null,
            ]);
        }

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
        $classes =
            ClassAccess::classes()
            ->orderBy('name')
            ->get();

        $chapters = collect();

        if ($this->class_id) {
            $class =
                ClassAccess::classOrFail(
                    (int)
                    $this->class_id
                );

            $chapters =
                $class
                ->chapters()
                ->orderBy('title')
                ->get();
        }

        return view(
            'livewire.pdf.form.create',
            [
                'classes' =>
                $classes,

                'chapters' =>
                $chapters,
            ]
        )->layout(
            'layouts.admin'
        );
    }
}
