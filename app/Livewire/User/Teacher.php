<?php

namespace App\Livewire\User;

use App\Models\Teacher as ModelsTeacher;
use Livewire\Component;

class Teacher extends Component
{
    public $title = 'Teacher';
    public function render()
    {
        $teachers = ModelsTeacher::with('user')->latest()->get();

        return view('livewire.user.teacher', compact('teachers'))->layout('layouts.admin', ['title' => $this->title]);
    }

    public function delete($id)
    {
        // dd($id);
        $teacher = ModelsTeacher::findOrFail($id);

        // hapus user yang terhubung
        $teacher->user()->delete();

        // hapus teacher
        $teacher->delete();

        session()->flash('success', 'Teacher deleted successfully');
    }
}
