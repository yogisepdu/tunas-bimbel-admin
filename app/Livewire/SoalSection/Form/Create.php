<?php

namespace App\Livewire\SoalSection\Form;

use App\Models\SoalOption;
use App\Models\SoalQuestion;
use App\Models\SoalSet;
use App\Support\ClassAccess;
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

    /**
     * Mengembalikan pilihan jawaban ke kondisi awal.
     */
    private function resetOptions(): void
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
        $validated = $this->validate([
            'soal_set_id' => [
                'required',
                'integer',
                'exists:soal_sets,id',
            ],
            'question' => [
                'required',
                'string',
            ],
            'correct_answer' => [
                'required',
                'in:A,B,C,D',
            ],
            'options.A' => [
                'required',
                'string',
            ],
            'options.B' => [
                'required',
                'string',
            ],
            'options.C' => [
                'required',
                'string',
            ],
            'options.D' => [
                'required',
                'string',
            ],
        ]);

        /*
         * Memeriksa apakah set TryOut dapat diakses user.
         *
         * Administrator dan admin:
         * - Dapat mengakses seluruh set.
         *
         * Teacher:
         * - Hanya dapat mengakses set dari kelas
         *   yang ditugaskan kepadanya.
         */
        $set = ClassAccess::setOrFail(
            (int) $validated['soal_set_id']
        );

        try {
            DB::transaction(function () use ($validated, $set) {
                /*
                 * Membuat pertanyaan TryOut.
                 */
                $soal = SoalQuestion::create([
                    'soal_set_id' => $set->id,
                    'question' => trim($validated['question']),
                    'correct_answer' => $validated['correct_answer'],
                ]);

                /*
                 * Membuat pilihan jawaban A, B, C, dan D.
                 */
                foreach ($validated['options'] as $key => $text) {
                    SoalOption::create([
                        'soal_question_id' => $soal->id,
                        'key' => $key,
                        'text' => trim($text),
                    ]);
                }

                /*
                 * Menambahkan jumlah soal pada set.
                 */
                $set->increment('total_questions');
            });

            /*
             * Mengosongkan form setelah berhasil.
             */
            $this->reset([
                'question',
                'soal_set_id',
            ]);

            $this->resetOptions();

            $this->correct_answer = 'A';

            session()->flash(
                'success',
                'Soal berhasil ditambahkan.'
            );

            return $this->redirect(
                route('soal-question.index'),
                navigate: true
            );
        } catch (\Throwable $exception) {
            /*
             * Simpan detail error pada log Laravel.
             */
            report($exception);

            session()->flash(
                'error',
                'Gagal menyimpan soal. Silakan coba kembali.'
            );

            /*
             * Tetap berada pada halaman form jika gagal.
             */
            return null;
        }
    }

    public function render()
    {
        /*
         * Mengambil ID kelas sesuai hak akses user.
         */
        $classIds = ClassAccess::classIds();

        /*
         * Filter set melalui:
         *
         * SoalSet -> SoalSection -> ClassRoom
         */
        $sets = SoalSet::query()
            ->whereHas('section', function ($query) use ($classIds) {
                $query->whereIn(
                    'class_id',
                    $classIds
                );
            })
            ->with([
                'section.classRoom',
            ])
            ->orderBy('title')
            ->get();

        return view('livewire.soal-section.form.create', [
            'sets' => $sets,
        ])->layout('layouts.admin');
    }
}
