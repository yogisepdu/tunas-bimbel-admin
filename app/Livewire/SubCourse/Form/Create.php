<?php

namespace App\Livewire\SubCourse\Form;

use App\Models\Chapter;
use App\Models\ClassRoom;
use Livewire\Component;

class Create extends Component
{
    public $class_id;
    public $title;
    public $description;

    public function save()
    {
        $this->validate([
            'class_id' => 'required|exists:classes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Chapter::create([
            'class_id' => $this->class_id,
            'title' => $this->title,
            'description' => $this->description
        ]);

        session()->flash('success', 'Chapter berhasil dibuat');

        return $this->redirect(route('sub-course.index'), navigate: true);
    }
    public function render()
    {
        $classes = ClassRoom::latest()->get();

        return view('livewire.sub-course.form.create',[
            'classes' => $classes
        ])->layout('layouts.admin');
    }
}
