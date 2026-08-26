<?php

namespace App\Livewire\SoalSection\Form;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SoalSet;
use App\Imports\SoalImport;
use App\Support\ClassAccess;
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

        $set = ClassAccess::setOrFail(
            (int) $this->soal_set_id
        );

        try {
            Excel::import(
                new SoalImport($set->id),
                $this->file
            );

            $this->reset(['file']);

            session()->flash('success', 'Import Excel berhasil');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal import file');
        }

        session()->flash('success', 'Soal berhasil ditambahkan');

        return $this->redirect(route('soal-question.index'), navigate: true);
    }

    public function render()
    {
        $classIds = ClassAccess::classIds();

        $sets = SoalSet::query()
            ->whereHas('section', function ($query) use ($classIds) {
                $query->whereIn('class_id', $classIds);
            })
            ->with('section.classRoom')
            ->get();

        return view('livewire.soal-section.form.import-excel', [
            'sets' => $sets,
        ])->layout('layouts.admin');
    }
}
