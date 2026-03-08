<?php

namespace App\Livewire\Class\Form;

use App\Models\ClassRoom;
use Livewire\Component;

class Create extends Component
{
    public $name;
    public $description;

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        ClassRoom::create([
            'name' => $this->name,
            'description' => $this->description
        ]);

        session()->flash('success', 'Class berhasil dibuat');

        return $this->redirect(route('course.index'), navigate: true);
    }
    public function render()
    {
        return view('livewire.class.form.create')->layout('layouts.admin');
    }
}
