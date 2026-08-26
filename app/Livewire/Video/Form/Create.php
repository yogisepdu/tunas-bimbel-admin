<?php

namespace App\Livewire\Video\Form;

use App\Models\Video;
use App\Support\ClassAccess;
use Livewire\Component;

class Create extends Component
{
    public $class_id;
    public $chapter_id;
    public $title;
    public $subtitle;
    public $youtube_id;

    public function updatedClassId()
    {
        $this->reset('chapter_id');
    }

    public function save()
    {
        $validated = $this->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'chapter_id' => 'required|integer|exists:chapters,id',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'youtube_id' => 'required|string|max:255',
        ]);

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

        Video::create([
            'chapter_id' => $chapter->id,
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'youtube_id' => $validated['youtube_id'],
        ]);

        session()->flash(
            'success',
            'Video berhasil dibuat'
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

        return view('livewire.video.form.create', [
            'classes' => $classes,
            'chapters' => $chapters,
        ])->layout('layouts.admin');
    }
}
