<?php

namespace App\Livewire\Linked;

use App\Models\Linked;
use Livewire\Component;

class Index extends Component
{
    public $linkId;
    public $link_name;
    public $link_url;

    protected $rules = [
        'link_name' => 'required|string',
        'link_url' => 'required|url',
    ];

    public function saveLink()
    {
        $this->validate();

        // 🔥 UPDATE MODE
        if ($this->linkId) {
            Linked::findOrFail($this->linkId)->update([
                'name' => $this->link_name,
                'url' => $this->link_url,
            ]);

            session()->flash('success', 'Link berhasil diupdate');
        } else {
            // 🔥 CEK DUPLIKAT
            if (Linked::where('name', $this->link_name)->exists()) {
                session()->flash('success', 'Link sudah ada, gunakan edit');
                return;
            }

            Linked::create([
                'name' => $this->link_name,
                'url' => $this->link_url,
            ]);

            session()->flash('success', 'Link berhasil ditambahkan');
        }

        $this->reset(['linkId', 'link_name', 'link_url']);
    }

    public function editLink($id)
    {
        $data = Linked::findOrFail($id);

        $this->linkId = $data->id;
        $this->link_name = $data->name;
        $this->link_url = $data->url;
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