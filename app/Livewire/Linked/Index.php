<?php

namespace App\Livewire\Linked;

use App\Models\Linked;
use Livewire\Component;

class Index extends Component
{
    public $link_name;
    public $link_url;

    public function saveLink()
    {
        $this->validate([
            'link_name' => 'required|in:peta_seleksi,informasi_beasiswa,informasi_kampus,grup_mentoring',
            'link_url' => 'required|url',
        ]);
        
        if (Linked::where('name', $this->link_name)->exists()) {
            session()->flash('success', 'Jenis link ini sudah ada!');
            return;
        }

        Linked::create([
            'name' => $this->link_name,
            'url' => $this->link_url,
        ]);

        $this->reset(['link_name', 'link_url']);

        session()->flash('success', 'Link berhasil ditambahkan');
    }

    public function deleteLink($id)
    {
        Linked::findOrFail($id)->delete();

        session()->flash('success', 'Link berhasil dihapus');
    }

    public function render()
    {
        return view('livewire.linked.index', [
            'links' => Linked::latest()->get()
        ])->layout('layouts.admin');
    }
}