<?php

namespace App\Livewire\Quiz;

use App\Support\ClassAccess;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    #[On('deleteClass')]
    public function delete($id)
    {
        $quiz = ClassAccess::quizOrFail(
            (int) $id
        );

        $quiz->delete();

        session()->flash(
            'success',
            'Quiz berhasil dihapus'
        );

        $this->dispatch('deleted');
    }

    public function render()
    {
        $classes = ClassAccess::classes()
            ->with('quizzes.questions')
            ->orderBy('name')
            ->get();

        return view('livewire.quiz.index', [
            'classes' => $classes,
        ])->layout('layouts.admin');
    }
}
