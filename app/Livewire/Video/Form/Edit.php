<?php

namespace App\Livewire\Video\Form;

use App\Support\ClassAccess;
use Livewire\Component;

class Edit extends Component
{
    public $video_id;
    public $class_id;
    public $chapter_id;
    public $title;
    public $subtitle;
    public $youtube_id;

    public function mount($id)
    {
        $video = ClassAccess::videoOrFail(
            (int) $id
        );

        $this->video_id = $video->id;
        $this->chapter_id = $video->chapter_id;
        $this->title = $video->title;
        $this->subtitle = $video->subtitle;
        $this->youtube_id = $video->youtube_id;
        $this->class_id = $video->chapter->class_id;
    }

    public function updatedClassId()
    {
        $this->reset('chapter_id');
    }

    public function update()
    {
        $validated = $this->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'chapter_id' => 'required|integer|exists:chapters,id',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'youtube_id' => 'required|string|max:255',
        ]);

        $video = ClassAccess::videoOrFail(
            (int) $this->video_id
        );

        $class = ClassAccess::classOrFail(
            (int) $validated['class_id']
        );

        $chapter = ClassAccess::chapterOrFail(
            (int) $validated['chapter_id']
        );

        if ((int) $chapter->class_id !== (int) $class->id) {
            $this->addError(
                'chapter_id',
                'Sub materi tidak sesuai dengan kelas yang dipilih.'
            );

            return;
        }

        $video->update([
            'chapter_id' => $chapter->id,
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'youtube_id' => $validated['youtube_id'],
        ]);

        session()->flash(
            'success',
            'Video berhasil diperbarui'
        );

        return $this->redirect(
            route('video.index'),
            navigate: true
        );
    }

    public function render()
    {
        $classes = ClassAccess::classes()
            ->orderBy('name')
            ->get();

        $chapters = collect();

        if ($this->class_id) {
            $class = ClassAccess::classOrFail(
                (int) $this->class_id
            );

            $chapters = $class->chapters()
                ->orderBy('title')
                ->get();
        }

        return view('livewire.video.form.edit', [
            'classes' => $classes,
            'chapters' => $chapters,
        ])->layout('layouts.admin');
    }
}
