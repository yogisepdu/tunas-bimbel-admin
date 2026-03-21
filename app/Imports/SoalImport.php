<?php

namespace App\Imports;

use App\Models\SoalQuestion;
use App\Models\SoalOption;
use App\Models\SoalSet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class SoalImport implements ToCollection
{
    protected $setId;

    public function __construct($setId)
    {
        $this->setId = $setId;
    }

    public function collection(Collection $rows)
    {
        // skip header
        $rows->shift();

        DB::transaction(function () use ($rows) {

            foreach ($rows as $row) {

                if (!$row[0]) continue;

                $soal = SoalQuestion::create([
                    'soal_set_id' => $this->setId,
                    'question' => trim($row[0]),
                    'correct_answer' => strtoupper($row[5]),
                ]);

                $options = [
                    'A' => $row[1],
                    'B' => $row[2],
                    'C' => $row[3],
                    'D' => $row[4],
                ];

                foreach ($options as $key => $text) {
                    SoalOption::create([
                        'soal_question_id' => $soal->id,
                        'key' => $key,
                        'text' => trim($text),
                    ]);
                }
            }

            SoalSet::where('id', $this->setId)
                ->increment('total_questions', $rows->count());
        });
    }
}