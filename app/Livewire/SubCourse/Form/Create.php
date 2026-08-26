<?php

namespace App\Livewire\SubCourse\Form;

use App\Models\Chapter;
use App\Support\ClassAccess;
use Livewire\Component;

class Create extends Component
{
    public $class_id;
    public $title;
    public $description;

    public function save()
    {
        $validated = $this->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $class = ClassAccess::classOrFail(
            (int) $validated['class_id']
        );

        Chapter::create([
            'class_id' => $class->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);

        session()->flash(
            'success',
            'Chapter berhasil dibuat'
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

        return view('livewire.sub-course.form.create', [
            'classes' => $classes,
        ])->layout('layouts.admin');
    }
}
