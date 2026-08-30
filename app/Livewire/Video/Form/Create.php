<?php

namespace App\Livewire\Video\Form;

use App\Models\Video;
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

    public $subtitle;

    public string $source_type = 'youtube';

    public string $youtube_id = '';

    public $video_file = null;

    public function updatedClassId(): void
    {
        $this->reset('chapter_id');
    }

    public function updatedSourceType(): void
    {
        $this->reset([
            'youtube_id',
            'video_file',
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

            'subtitle' => [
                'nullable',
                'string',
                'max:255',
            ],

            'source_type' => [
                'required',
                'in:youtube,private_file',
            ],
        ];

        if (
            $this->source_type === 'youtube'
        ) {
            $rules['youtube_id'] = [
                'required',
                'string',
                'max:255',
            ];
        } else {
            $rules['video_file'] = [
                'required',
                'file',
                'mimes:mp4,webm,mov',
                'max:204800',
            ];
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'class_id.required' =>
            'Kelas wajib dipilih.',

            'chapter_id.required' =>
            'Sub materi wajib dipilih.',

            'title.required' =>
            'Judul video wajib diisi.',

            'youtube_id.required' =>
            'YouTube ID wajib diisi.',

            'video_file.required' =>
            'File video wajib dipilih.',

            'video_file.mimes' =>
            'Video harus berformat MP4, WEBM, atau MOV.',

            'video_file.max' =>
            'Ukuran video maksimal 200 MB.',
        ];
    }

    public function save()
    {
        $validated =
            $this->validate();

        /*
        |--------------------------------------------------------------------------
        | Validasi Akses Kelas
        |--------------------------------------------------------------------------
        */

        $class =
            ClassAccess::classOrFail(
                (int) $validated['class_id']
            );

        $chapter =
            ClassAccess::chapterOrFail(
                (int) $validated['chapter_id']
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

        /*
        |--------------------------------------------------------------------------
        | PRIVATE VIDEO
        |--------------------------------------------------------------------------
        */

        if (
            $this->source_type
            === 'private_file'
        ) {
            /*
             * Simpan dulu file ke lokasi FINAL.
             *
             * JANGAN memanggil:
             *
             * $this->video_file->getSize()
             * $this->video_file->getMimeType()
             *
             * setelah store().
             */
            $path =
                $this->video_file->store(
                    'learning/videos/'
                        . $chapter->id,
                    'local'
                );

            try {
                /*
                 * Ambil metadata dari FILE FINAL,
                 * bukan dari livewire-tmp.
                 */
                $metadata =
                    $this->getStoredFileMetadata(
                        $path
                    );

                Video::create([
                    'chapter_id' =>
                    $chapter->id,

                    'title' =>
                    trim(
                        $validated['title']
                    ),

                    'subtitle' =>
                    ! empty($validated['subtitle'])
                        ? trim(
                            $validated['subtitle']
                        )
                        : null,

                    /*
                     * Tetap string kosong agar aman
                     * jika youtube_id pada database lama
                     * masih NOT NULL.
                     */
                    'youtube_id' =>
                    '',

                    'source_type' =>
                    'private_file',

                    'video_path' =>
                    $path,

                    'video_mime_type' =>
                    $metadata['mime'],

                    'video_size' =>
                    $metadata['size'],
                ]);
            } catch (\Throwable $e) {
                /*
                 * Kalau database gagal,
                 * jangan tinggalkan file orphan.
                 */
                if (
                    $path
                    && Storage::disk('local')
                    ->exists($path)
                ) {
                    Storage::disk('local')
                        ->delete($path);
                }

                throw $e;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | YOUTUBE
        |--------------------------------------------------------------------------
        */ else {
            Video::create([
                'chapter_id' =>
                $chapter->id,

                'title' =>
                trim(
                    $validated['title']
                ),

                'subtitle' =>
                ! empty($validated['subtitle'])
                    ? trim(
                        $validated['subtitle']
                    )
                    : null,

                'youtube_id' =>
                trim(
                    $validated['youtube_id']
                ),

                'source_type' =>
                'youtube',

                'video_path' =>
                null,

                'video_mime_type' =>
                null,

                'video_size' =>
                null,
            ]);
        }

        session()->flash(
            'success',
            'Video berhasil dibuat.'
        );

        return $this->redirect(
            route('video.index'),
            navigate: true
        );
    }

    /**
     * Ambil metadata dari file yang SUDAH
     * tersimpan pada storage final.
     */
    private function getStoredFileMetadata(
        string $path
    ): array {
        $disk =
            Storage::disk('local');

        $size = null;

        $mime = null;

        /*
        |--------------------------------------------------------------------------
        | File Size
        |--------------------------------------------------------------------------
        */

        try {
            if (
                $disk->exists($path)
            ) {
                $size =
                    (int) $disk->size(
                        $path
                    );
            }
        } catch (\Throwable $e) {
            /*
             * Metadata ukuran bukan alasan
             * untuk menggagalkan upload.
             */
            $size = null;
        }

        /*
        |--------------------------------------------------------------------------
        | MIME
        |--------------------------------------------------------------------------
        */

        try {
            if (
                $disk->exists($path)
            ) {
                $mime =
                    $disk->mimeType(
                        $path
                    );
            }
        } catch (\Throwable $e) {
            $mime = null;
        }

        /*
        |--------------------------------------------------------------------------
        | MIME Fallback
        |--------------------------------------------------------------------------
        */

        if (! $mime) {
            $extension =
                strtolower(
                    pathinfo(
                        $path,
                        PATHINFO_EXTENSION
                    )
                );

            $mime = match ($extension) {
                'mp4' =>
                'video/mp4',

                'webm' =>
                'video/webm',

                'mov' =>
                'video/quicktime',

                default =>
                'application/octet-stream',
            };
        }

        return [
            'size' =>
            $size,

            'mime' =>
            $mime,
        ];
    }

    public function render()
    {
        $classes =
            ClassAccess::classes()
            ->orderBy('name')
            ->get();

        $chapters =
            collect();

        if ($this->class_id) {
            $class =
                ClassAccess::classOrFail(
                    (int) $this->class_id
                );

            $chapters =
                $class
                ->chapters()
                ->orderBy('title')
                ->get();
        }

        return view(
            'livewire.video.form.create',
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
