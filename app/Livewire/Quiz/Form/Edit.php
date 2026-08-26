<?php

namespace App\Livewire\Quiz\Form;

use App\Models\ClassRoom;
use App\Models\Quiz;
use Livewire\Component;
use App\Support\ClassAccess;

class Edit extends Component
{
    public $quizId;

    public $class_id;
    public $title;
    public $duration;

    protected $rules = [
        'class_id' => 'required|exists:classes,id',
        'title' => 'required|string|max:255',
        'duration' => 'required|integer|min:1'
    ];

    public function mount($id)
    {
        $quiz = ClassAccess::quizOrFail(
            (int) $id
        );

        $this->quizId = $quiz->id;
        $this->class_id = $quiz->class_id;
        $this->title = $quiz->title;
        $this->duration = $quiz->duration;
    }

    public function update()
    {
        $this->validate();

        $quiz = ClassAccess::quizOrFail(
            (int) $this->quizId
        );

        $class = ClassAccess::classOrFail(
            (int) $this->class_id
        );

        $quiz->update([
            'class_id' => $class->id,
            'title' => $this->title,
            'duration' => $this->duration,
        ]);

        session()->flash('success', 'Quiz berhasil diperbarui');

        return $this->redirect(route('quiz.index'), navigate: true);
    }

    public function render()
    {
        $classes = ClassAccess::classes()
            ->orderBy('name')
            ->get();

        return view('livewire.quiz.form.edit', [
            'classes' => $classes,
        ])->layout('layouts.admin');
    }
}
