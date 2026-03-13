<?php

namespace App\Livewire\Quizez;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\On;

class Index extends Component
{
    public $quiz;

    public function mount(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    public function render()
    {
        return view('livewire.quizez.index', [
            'questions' => Question::where('quiz_id', $this->quiz->id)->get()
        ])->layout('layouts.admin');
    }

    #[On('deleteClass')]
    public function deleteQuestion($id)
    {
        $question = Question::findOrFail($id);

        $question->delete();

        session()->flash('success', 'Soal berhasil dihapus');
        $this->dispatch('deleted');
    }
}
