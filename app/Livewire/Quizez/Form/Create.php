<?php

namespace App\Livewire\Quizez\Form;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
            'question' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|string',
        ]);

        $imagePath = null;

        if ($this->image) {

            $quiz = Quiz::findOrFail($this->quiz_id);

            // folder berdasarkan title quiz
            $folder = 'questions/' . Str::slug($quiz->title);

            // lokasi storage
            $storagePath = storage_path('app/public/' . $folder);

            // buat folder jika belum ada
            if (!File::exists($storagePath)) {
                File::makeDirectory($storagePath, 0755, true);
            }

            // nama file unik
            $filename = Str::uuid() . '.jpg';

            // manager image
            $manager = new ImageManager(new Driver());

            // proses gambar
            $image = $manager
                ->read($this->image->getRealPath())
                ->orient()                // perbaiki rotasi gambar
                ->scaleDown(width: 1200)  // resize max 1200px
                ->toJpeg(85);             // compress

            // simpan gambar
            $image->save($storagePath . '/' . $filename);

            // path untuk database
            $imagePath = $folder . '/' . $filename;
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

        session()->flash('success', 'Soal berhasil ditambahkan');

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
