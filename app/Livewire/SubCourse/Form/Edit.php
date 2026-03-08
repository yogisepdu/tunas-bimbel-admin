<?php

namespace App\Livewire\SubCourse\Form;

use App\Models\Chapter;
use App\Models\ClassRoom;
use Livewire\Component;

class Edit extends Component
{

    public $chapterId;
    public $class_id;
    public $title;
    public $description;

    public function mount($id)
    {
        $chapter = Chapter::findOrFail($id);

        $this->chapterId = $chapter->id;
        $this->class_id = $chapter->class_id;
        $this->title = $chapter->title;
        $this->description = $chapter->description;
    }

    public function update()
    {
        $this->validate([
            'class_id' => 'required|exists:classes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Chapter::findOrFail($this->chapterId)->update([
            'class_id' => $this->class_id,
            'title' => $this->title,
            'description' => $this->description
        ]);

        session()->flash('success', 'Chapter berhasil diperbarui');

        return $this->redirect(route('sub-course.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.sub-course.form.edit', [
            'classes' => ClassRoom::orderBy('name')->get()
        ])->layout('layouts.admin');
    }
}
