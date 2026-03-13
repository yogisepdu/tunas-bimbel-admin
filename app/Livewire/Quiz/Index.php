<?php

namespace App\Livewire\Quiz;

use App\Models\ClassRoom;
use App\Models\Quiz;
use Livewire\Component;
use Livewire\Attributes\On;

class Index extends Component
{
    public function render()
    {
        return view('livewire.quiz.index', [
            'classes' => ClassRoom::with('quizzes.questions')->get()
        ])->layout('layouts.admin');
    }

    #[On('deleteClass')]
    public function delete($id)
    {
        $quiz = Quiz::findOrFail($id);

        // hapus quiz
        $quiz->delete();

        session()->flash('success', 'Quiz berhasil dihapus');
    }
}
