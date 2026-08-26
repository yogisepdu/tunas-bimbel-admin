<?php

namespace App\Livewire\Quiz\Form;

use App\Models\Chapter;
use App\Models\ClassRoom;
use App\Models\Quiz;
use Livewire\Component;
use App\Support\ClassAccess;

class Create extends Component
{
    public $class_id;
    public $title;
    public $duration;

    protected $rules = [
        'class_id' => 'required|exists:classes,id',
        'title' => 'required|string|max:255',
        'duration' => 'required|integer|min:1'
    ];

    public function save()
    {
        $this->validate();

        $class = ClassAccess::classOrFail(
            (int) $this->class_id
        );

        Quiz::create([
            'class_id' => $class->id,
            'title' => $this->title,
            'duration' => $this->duration,
        ]);

        session()->flash('success', 'Quiz berhasil dibuat');

        return $this->redirect(route('quiz.index'), navigate: true);
    }

    public function render()
    {
        $classes = ClassAccess::classes()
            ->orderBy('name')
            ->get();

        return view('livewire.quiz.form.create', [
            'classes' => $classes,
        ])->layout('layouts.admin');
    }
}
