<?php

namespace App\Livewire\Quizez;

use App\Models\Quiz;
use App\Support\ClassAccess;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public $quiz;

    public function mount(Quiz $quiz)
    {
        $this->quiz = ClassAccess::quizOrFail(
            (int) $quiz->id
        );
    }

    #[On('deleteClass')]
    public function deleteQuestion($id)
    {
        $question = ClassAccess::questionOrFail(
            (int) $id
        );

        abort_unless(
            (int) $question->quiz_id === (int) $this->quiz->id,
            404
        );

        $question->delete();

        session()->flash(
            'success',
            'Soal berhasil dihapus'
        );

        $this->dispatch('deleted');
    }

    public function render()
    {
        $questions = $this->quiz
            ->questions()
            ->latest()
            ->get();

        return view('livewire.quizez.index', [
            'questions' => $questions,
        ])->layout('layouts.admin');
    }
}
