<?php

namespace App\Livewire\Video\Form;

use App\Support\ClassAccess;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public int $video_id;

    public $class_id;

    public $chapter_id;

    public $title;

    public $subtitle;

    public string $source_type = 'youtube';

    public string $youtube_id = '';

    public $video_file = null;

    public ?string $existingVideoPath = null;

    public function mount($id): void
    {
        $video =
            ClassAccess::videoOrFail(
                (int) $id
            );

        $this->video_id =
            (int) $video->id;

        $this->chapter_id =
            $video->chapter_id;

        $this->title =
            $video->title;

        $this->subtitle =
            $video->subtitle;

        $this->source_type =
            $video->source_type
            ?: 'youtube';

        $this->youtube_id =
            (string) $video->youtube_id;

        $this->existingVideoPath =
            $video->video_path;

        $this->class_id =
            $video->chapter->class_id;
    }

    public function updatedClassId(): void
    {
        $this->reset(
            'chapter_id'
        );
    }

    public function updatedSourceType(): void
    {
        $this->video_file = null;

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
            $this->source_type
            === 'youtube'
        ) {
            $rules['youtube_id'] = [
                'required',
                'string',
                'max:255',
            ];
        } else {
            $rules['video_file'] = [
                $this->existingVideoPath
                    ? 'nullable'
                    : 'required',

                'file',
                'mimes:mp4,webm,mov',
                'max:204800',
            ];
        }

        return $rules;
    }

    public function update()
    {
        $validated =
            $this->validate();

        $video =
            ClassAccess::videoOrFail(
                $this->video_id
            );

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

        $oldPath =
            $video->video_path;

        /*
        |--------------------------------------------------------------------------
        | PRIVATE FILE
        |--------------------------------------------------------------------------
        */

        if (
            $this->source_type
            === 'private_file'
        ) {
            $path =
                $oldPath;

            $mime =
                $video->video_mime_type;

            $size =
                $video->video_size;

            /*
             * Kalau user upload video baru.
             */
            if ($this->video_file) {
                $path =
                    $this->video_file
                    ->store(
                        'learning/videos/'
                            . $chapter->id,
                        'local'
                    );

                /*
                 * Baca metadata dari file final.
                 */
                $metadata =
                    $this->getStoredFileMetadata(
                        $path
                    );

                $mime =
                    $metadata['mime'];

                $size =
                    $metadata['size'];
            }

            try {
                $video->update([
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
                    '',

                    'source_type' =>
                    'private_file',

                    'video_path' =>
                    $path,

                    'video_mime_type' =>
                    $mime,

                    'video_size' =>
                    $size,
                ]);
            } catch (\Throwable $e) {
                /*
                 * Kalau DB gagal,
                 * hapus hanya file BARU.
                 */
                if (
                    $this->video_file
                    && $path
                    && $path !== $oldPath
                    && Storage::disk('local')
                    ->exists($path)
                ) {
                    Storage::disk('local')
                        ->delete($path);
                }

                throw $e;
            }

            /*
             * Database sudah sukses.
             * Baru hapus video lama.
             */
            if (
                $this->video_file
                && $oldPath
                && $oldPath !== $path
                && Storage::disk('local')
                ->exists($oldPath)
            ) {
                Storage::disk('local')
                    ->delete($oldPath);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | YOUTUBE
        |--------------------------------------------------------------------------
        */ else {
            $video->update([
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

            /*
             * Jika sebelumnya private dan sekarang
             * menjadi YouTube, hapus private file lama.
             */
            if (
                $oldPath
                && Storage::disk('local')
                ->exists($oldPath)
            ) {
                Storage::disk('local')
                    ->delete($oldPath);
            }
        }

        session()->flash(
            'success',
            'Video berhasil diperbarui.'
        );

        return $this->redirect(
            route('video.index'),
            navigate: true
        );
    }

    private function getStoredFileMetadata(
        string $path
    ): array {
        $disk =
            Storage::disk('local');

        $size = null;

        $mime = null;

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
            $size = null;
        }

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
            'livewire.video.form.edit',
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
