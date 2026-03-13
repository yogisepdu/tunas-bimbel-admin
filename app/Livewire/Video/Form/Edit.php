<?php

namespace App\Livewire\Video\Form;

use App\Models\Chapter;
use App\Models\ClassRoom;
use App\Models\Video;
use Livewire\Component;

class Edit extends Component
{
    public $video_id;

    public $class_id;
    public $chapter_id;
    public $title;
    public $subtitle;
    public $youtube_id;

    public function mount($id)
    {
        $video = Video::with('chapter')->findOrFail($id);

        $this->video_id = $video->id;
        $this->chapter_id = $video->chapter_id;
        $this->title = $video->title;
        $this->subtitle = $video->subtitle;
        $this->youtube_id = $video->youtube_id;

        // ambil class dari chapter
        $this->class_id = $video->chapter->class_id;
    }

    public function update()
    {
        $this->validate([
            'chapter_id' => 'required',
            'title' => 'required',
            'subtitle' => 'nullable',
            'youtube_id' => 'required'
        ]);

        $video = Video::findOrFail($this->video_id);

        $video->update([
            'chapter_id' => $this->chapter_id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'youtube_id' => $this->youtube_id
        ]);

        session()->flash('success','Video berhasil diupdate');

        return $this->redirect(route('video.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.video.form.edit',[
            'classes' => ClassRoom::all(),
            'chapters' => $this->class_id
                ? Chapter::where('class_id',$this->class_id)->get()
                : []
        ])->layout('layouts.admin');
    }
}
