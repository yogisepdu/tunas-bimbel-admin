<?php

namespace App\Livewire\Quizez\Form;

use App\Models\Question;
use App\Models\Quiz;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Create extends Component
{
    use WithFileUploads;

    public $quiz_id;
    public $question;
    public $image;

    public $option_a;
    public $option_b;
    public $option_c;
    public $option_d;

    public $correct_answer;

    public function mount($quiz)
    {
        $this->quiz_id = $quiz;
    }

    public function save()
    {
        $this->validate([
            'question' => 'required',
            'image' => 'nullable|image|max:2048',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'correct_answer' => 'required'
        ]);

        $imagePath = null;

        if ($this->image) {

            // ambil quiz
            $quiz = Quiz::findOrFail($this->quiz_id);

            // buat nama folder berdasarkan title quiz
            $folder = 'questions/' . Str::slug($quiz->title);

            // simpan gambar ke folder tersebut
            $imagePath = $this->image->store($folder, 'public');
        }

        Question::create([
            'quiz_id' => $this->quiz_id,
            'question' => $this->question,
            'image' => $imagePath,
            'option_a' => $this->option_a,
            'option_b' => $this->option_b,
            'option_c' => $this->option_c,
            'option_d' => $this->option_d,
            'correct_answer' => $this->correct_answer,
        ]);

        session()->flash('success','Soal berhasil ditambahkan');
        return $this->redirect(
            route('question.index', ['quiz' => $this->quiz_id]),
            navigate: true
        );
    }

    public function render()
    {
        return view('livewire.quizez.form.create',[
            'quizzes' => Quiz::all()
        ])->layout('layouts.admin');
    }
}
