<?php

namespace App\Livewire\Packages\Form;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Packages;
use App\Models\ClassRoom;

class Create extends Component
{
    use WithFileUploads;

    public $name, $description, $price;
    public $selectedClasses = [];
    public $image; // 🔥 tambah ini

    public function getSelectedCountProperty()
    {
        return count($this->selectedClasses ?? []);
    }


    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048', // 🔥 validasi gambar
        ]);

        // 🔥 upload gambar
        $imagePath = null;

        if ($this->image) {
            $imagePath = $this->image->store('packages', 'public');
        }

        // 🔥 SIMPAN PACKAGE
        $package = Packages::create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image' => $imagePath,
        ]);

        // 🔥 SIMPAN RELASI
        $package->classes()->sync($this->selectedClasses);

        session()->flash('success', 'Paket berhasil dibuat');

        return redirect()->route('packages.index');
    }

    public function render()
    {
        return view('livewire.packages.form.create', [
            'classes' => ClassRoom::all()
        ])->layout('layouts.admin');
    }
}