<?php

namespace App\Livewire\Packages;

use App\Models\Packages;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    #[On('deleteClass')]
    public function delete($id)
    {
        $package = Packages::findOrFail($id);

        // 🔥 HAPUS FILE GAMBAR JIKA ADA
        if ($package->image && Storage::disk('public')->exists($package->image)) {
            Storage::disk('public')->delete($package->image);
        }

        // 🔥 HAPUS DATA
        $package->delete();

        session()->flash('success', 'Paket berhasil dihapus');

        $this->dispatch('deleted');
    }
    public function render()
    {
        return view('livewire.packages.index',[
            'packages' => Packages::with('classes')->latest()->get()
        ])->layout('layouts.admin');
    }
}
