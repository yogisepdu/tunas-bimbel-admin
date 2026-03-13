<?php

namespace App\Livewire\Video\Form;

use App\Models\Chapter;
use App\Models\ClassRoom;
use App\Models\Video;
use Livewire\Component;

class Create extends Component
{
    public $class_id;
    public $chapter_id;
    public $title;
    public $subtitle;
    public $youtube_id;

    public function save()
    {
        $this->validate([
            'chapter_id' => 'required',
            'title' => 'required',
            'subtitle' => 'nullable',
            'youtube_id' => 'required'
        ]);

        Video::create([
            'chapter_id' => $this->chapter_id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'youtube_id' => $this->youtube_id
        ]);

        session()->flash('success','Video berhasil dibuat');

        return $this->redirect(route('video.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.video.form.create',[
        'classes' => ClassRoom::all(),
        'chapters' => $this->class_id
            ? Chapter::where('class_id',$this->class_id)->get()
            : []
    ])->layout('layouts.admin');
    }
}
