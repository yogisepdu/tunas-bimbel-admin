<?php

namespace App\Livewire\User;

use App\Models\Student as ModelsStudent;
use Livewire\Component;

class Student extends Component
{
    public $title = 'Student';

    public function render()
    {
        $students = ModelsStudent::with('user')->latest()->get();

        return view('livewire.user.student', compact('students'))
            ->layout('layouts.admin', ['title' => $this->title]);
    }

    public function delete($id)
    {
        // dd($id);
        $student = ModelsStudent::findOrFail($id);

        // hapus user yang terhubung
        $student->user()->delete();

        // hapus student
        $student->delete();

        session()->flash('success', 'Student deleted successfully');
    }
}
