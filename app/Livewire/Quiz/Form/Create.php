<?php

namespace App\Livewire\Quiz\Form;

use App\Models\Chapter;
use App\Models\ClassRoom;
use App\Models\Quiz;
use Livewire\Component;

class Create extends Component
{
    public $class_id;
    public $title;
    public $duration;

    protected $rules = [
        'class_id' => 'required|exists:classes,id',
        'title' => 'required|string|max:255',
        'duration' => 'required|integer|min:1'
    ];

    public function save()
    {
        $this->validate();

        Quiz::create([
            'class_id' => $this->class_id,
            'title' => $this->title,
            'duration' => $this->duration
        ]);

        session()->flash('success','Quiz berhasil dibuat');

        return $this->redirect(route('quiz.index'), navigate:true);
    }

    public function render()
    {
        return view('livewire.quiz.form.create',[
            'classes' => ClassRoom::all()
        ])->layout('layouts.admin');
    }
}
