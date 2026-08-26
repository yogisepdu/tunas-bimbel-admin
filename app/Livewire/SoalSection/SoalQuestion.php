<?php

namespace App\Livewire\SoalSection;

use App\Models\SoalSection;
use App\Support\ClassAccess;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SoalQuestion extends Component
{
    public function delete($id)
    {
        $question = ClassAccess::soalQuestionOrFail(
            (int) $id
        );

        DB::transaction(function () use ($question) {
            $set = $question->set;

            $question->delete();

            if ($set && $set->total_questions > 0) {
                $set->decrement('total_questions');
            }
        });

        session()->flash(
            'success',
            'Soal berhasil dihapus'
        );
    }

    public function render()
    {
        $sections = SoalSection::query()
            ->whereIn(
                'class_id',
                ClassAccess::classIds()
            )
            ->with([
                'classRoom',
                'sets.questions.options',
            ])
            ->latest()
            ->get();

        return view('livewire.soal-section.soal-question', [
            'sections' => $sections,
        ])->layout('layouts.admin');
    }
}
