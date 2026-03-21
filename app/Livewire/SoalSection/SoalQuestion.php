<?php

namespace App\Livewire\SoalSection;

use Livewire\Component;
use App\Models\SoalSection;
use App\Models\SoalQuestion as SoalQuestionModel;
use Illuminate\Support\Facades\DB;

class SoalQuestion extends Component
{
    // ================= DELETE =================
    public function delete($id)
    {
        $question = SoalQuestionModel::with(['options', 'set'])->find($id);

        if (!$question) return;

        DB::transaction(function () use ($question) {

            // hapus options
            $question->options()->delete();

            // kurangi total soal di set
            if ($question->set) {
                $question->set->decrement('total_questions');
            }

            // hapus soal
            $question->delete();
        });

        session()->flash('success', 'Soal berhasil dihapus');
    }

    // ================= RENDER =================
    public function render()
    {
        return view('livewire.soal-section.soal-question', [
            'sections' => SoalSection::with([
                'sets.questions.options'
            ])->latest()->get()
        ])->layout('layouts.admin');
    }
}