<?php

namespace App\Livewire\Class\Form;

use App\Models\ClassRoom;
use Livewire\Component;

class Edit extends Component
{
    public $classId;
    public $name;
    public $description;

    public function mount($id)
    {
        $class = ClassRoom::findOrFail($id);

        $this->classId = $class->id;
        $this->name = $class->name;
        $this->description = $class->description;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        ClassRoom::findOrFail($this->classId)->update([
            'name' => $this->name,
            'description' => $this->description
        ]);

        session()->flash('success', 'Kelas berhasil diupdate');

        return $this->redirect(route('course.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.class.form.edit')->layout('layouts.admin');
    }
}
