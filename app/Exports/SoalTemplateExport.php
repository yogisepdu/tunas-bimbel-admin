<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;

class SoalTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            // HEADER
            ['question', 'A', 'B', 'C', 'D', 'correct'],

            // CONTOH
            [
                'Apa ibu kota Indonesia?',
                'Jakarta',
                'Bandung',
                'Surabaya',
                'Medan',
                'A'
            ],
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }
}
