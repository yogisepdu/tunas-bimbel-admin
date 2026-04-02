<?php

namespace App\Livewire\Linked;

use App\Models\Announcement;
use Livewire\Component;
use Livewire\Attributes\On;

class AnnouncementController extends Component
{
    public $category, $title, $description;
    public $announcementId;

    public function save()
    {
        Announcement::create([
            'category' => $this->category,
            'title' => $this->title,
            'description' => $this->description,
            'is_new' => true,
            'published_at' => now(),
        ]);

        $this->reset(['category', 'title', 'description']);

        session()->flash('success', 'Berhasil tambah pengumuman');
    }

    public function edit($id)
    {
        $data = Announcement::findOrFail($id);

        $this->announcementId = $data->id;
        $this->category = $data->category;
        $this->title = $data->title;
        $this->description = $data->description;
    }

    public function update()
    {
        $data = Announcement::findOrFail($this->announcementId);

        $data->update([
            'category' => $this->category,
            'title' => $this->title,
            'description' => $this->description,
        ]);

        $this->reset(['announcementId', 'category', 'title', 'description']);

        session()->flash('success', 'Berhasil update pengumuman');
    }

    #[On('deleteClass')]
    public function delete($id)
    {
        Announcement::findOrFail($id)->delete();

        session()->flash('success', 'Pengumuman berhasil dihapus');

        $this->dispatch('deleted');
    }
    
    public function render()
    {
        return view('livewire.linked.announcement-controller',[
            'announcements' => Announcement::latest()->get(),
        ])->layout('layouts.admin');
    }
}
