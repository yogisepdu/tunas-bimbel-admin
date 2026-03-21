<?php

namespace App\Livewire\SoalSection\Form;

use App\Models\SoalOption;
use App\Models\SoalQuestion;
use App\Models\SoalSet;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public $soal_set_id;
    public $question;
    public $correct_answer = 'A';

    public $options = [
        'A' => '',
        'B' => '',
        'C' => '',
        'D' => '',
    ];

    // 🔥 RESET OPTIONS DEFAULT
    private function resetOptions()
    {
        $this->options = [
            'A' => '',
            'B' => '',
            'C' => '',
            'D' => '',
        ];
    }

    public function save()
    {
        $this->validate([
            'soal_set_id' => 'required|exists:soal_sets,id',
            'question' => 'required|string',
            'correct_answer' => 'required|in:A,B,C,D',
            'options.A' => 'required|string',
            'options.B' => 'required|string',
            'options.C' => 'required|string',
            'options.D' => 'required|string',
        ]);

        try {
            DB::transaction(function () {

                // 🔥 TRIM DATA
                $question = trim($this->question);

                $soal = SoalQuestion::create([
                    'soal_set_id' => $this->soal_set_id,
                    'question' => $question,
                    'correct_answer' => $this->correct_answer,
                ]);

                foreach ($this->options as $key => $text) {
                    SoalOption::create([
                        'soal_question_id' => $soal->id,
                        'key' => $key,
                        'text' => trim($text),
                    ]);
                }

                // 🔥 SAFE INCREMENT
                $set = SoalSet::find($this->soal_set_id);
                if ($set) {
                    $set->increment('total_questions');
                }
            });

            // 🔥 RESET FORM
            $this->reset(['question', 'soal_set_id']);
            $this->resetOptions();
            $this->correct_answer = 'A';

            session()->flash('success', 'Soal berhasil ditambahkan');

        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal menyimpan soal');
            logger()->error($e);
        }

        session()->flash('success','Soal berhasil ditambahkan');

        return $this->redirect(route('soal-question.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.soal-section.form.create', [
            'sets' => SoalSet::with('section')->get()
        ])->layout('layouts.admin');
    }
}