<?php

namespace App\Livewire\SubCourse\Form;

use App\Support\ClassAccess;
use Livewire\Component;

class Edit extends Component
{
    public $chapterId;
    public $class_id;
    public $title;
    public $description;

    public function mount($id)
    {
        $chapter = ClassAccess::chapterOrFail(
            (int) $id
        );

        $this->chapterId = $chapter->id;
        $this->class_id = $chapter->class_id;
        $this->title = $chapter->title;
        $this->description = $chapter->description;
    }

    public function update()
    {
        $validated = $this->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $chapter = ClassAccess::chapterOrFail(
            (int) $this->chapterId
        );

        $class = ClassAccess::classOrFail(
            (int) $validated['class_id']
        );

        $chapter->update([
            'class_id' => $class->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        session()->flash(
            'success',
            'Chapter berhasil diperbarui'
        );

        return $this->redirect(
            route('sub-course.index'),
            navigate: true
        );
    }

    public function render()
    {
        $classes = ClassAccess::classes()
            ->orderBy('name')
            ->get();

        return view('livewire.sub-course.form.edit', [
            'classes' => $classes,
        ])->layout('layouts.admin');
    }
}
