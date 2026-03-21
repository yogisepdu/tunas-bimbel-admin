<?php

namespace App\Livewire\SoalSection\Form;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SoalSet;
use App\Imports\SoalImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportExcel extends Component
{
    use WithFileUploads;

    public $soal_set_id;
    public $file;

    public function import()
    {
        $this->validate([
            'soal_set_id' => 'required|exists:soal_sets,id',
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(
                new SoalImport($this->soal_set_id),
                $this->file
            );

            $this->reset(['file']);

            session()->flash('success', 'Import Excel berhasil');

        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal import file');
        }

        session()->flash('success','Soal berhasil ditambahkan');

        return $this->redirect(route('soal-question.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.soal-section.form.import-excel', [
            'sets' => SoalSet::with('section')->get()
        ])->layout('layouts.admin');
    }
}