<?php

namespace App\Livewire\Pdf\Form;

use App\Models\MateriPdf;
use App\Support\ClassAccess;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public int $pdfId;

    public $class_id;
    public $chapter_id;
    public $title;

    public string $storage_type =
    'external_url';

    public string $pdf_url = '';

    public $pdf_file = null;

    public ?string $existingPrivatePath =
    null;

    public function mount($id): void
    {
        $pdf =
            ClassAccess::pdfOrFail(
                (int) $id
            );

        $this->pdfId =
            (int) $pdf->id;

        $this->chapter_id =
            $pdf->chapter_id;

        $this->title =
            $pdf->title;

        $this->storage_type =
            $pdf->storage_type
            ?: 'external_url';

        $this->class_id =
            $pdf
            ->chapter
            ->class_id;

        if (
            $this->storage_type
            === 'private_file'
        ) {
            $this->existingPrivatePath =
                $pdf->pdf_url;

            $this->pdf_url = '';
        } else {
            $this->pdf_url =
                (string)
                $pdf->pdf_url;
        }
    }

    public function updatedClassId(): void
    {
        $this->reset('chapter_id');
    }

    public function updatedStorageType(): void
    {
        $this->pdf_file = null;
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
                $this->existingPrivatePath
                    ? 'nullable'
                    : 'required',

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

    public function update()
    {
        $validated =
            $this->validate();

        $pdf =
            ClassAccess::pdfOrFail(
                $this->pdfId
            );

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

        $oldPrivatePath =
            $pdf->storage_type
            === 'private_file'
            ? $pdf->pdf_url
            : null;

        if (
            $this->storage_type
            === 'private_file'
        ) {
            $path =
                $oldPrivatePath;

            $mime =
                $pdf->file_mime_type;

            $size =
                $pdf->file_size;

            if ($this->pdf_file) {
                $path =
                    $this->pdf_file
                    ->store(
                        'learning/pdfs/'
                            . $chapter->id,
                        'local'
                    );

                $mime =
                    $this
                    ->pdf_file
                    ->getMimeType();

                $size =
                    $this
                    ->pdf_file
                    ->getSize();
            }

            $pdf->update([
                'chapter_id' =>
                $chapter->id,

                'title' =>
                trim(
                    $validated['title']
                ),

                'pdf_url' =>
                $path,

                'storage_type' =>
                'private_file',

                'file_mime_type' =>
                $mime,

                'file_size' =>
                $size,
            ]);

            if (
                $this->pdf_file
                && $oldPrivatePath
                && $oldPrivatePath
                !== $path
                && Storage::disk('local')
                ->exists(
                    $oldPrivatePath
                )
            ) {
                Storage::disk('local')
                    ->delete(
                        $oldPrivatePath
                    );
            }
        } else {
            $pdf->update([
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

            if (
                $oldPrivatePath
                && Storage::disk('local')
                ->exists(
                    $oldPrivatePath
                )
            ) {
                Storage::disk('local')
                    ->delete(
                        $oldPrivatePath
                    );
            }
        }

        session()->flash(
            'success',
            'Materi PDF berhasil diperbarui.'
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
            'livewire.pdf.form.edit',
            compact(
                'classes',
                'chapters'
            )
        )->layout(
            'layouts.admin'
        );
    }
}
