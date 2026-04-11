<?php

namespace App\Livewire\Packages\Form;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Packages;
use App\Models\ClassRoom;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;

    public $packageId;

    public $name, $description, $price;
    public $selectedClasses = [];
    public $image;

    public $oldImage; // 🔥 simpan gambar lama

    // 🔥 load data pertama kali
    public function mount($id)
    {
        $package = Packages::with('classes')->findOrFail($id);

        $this->packageId = $package->id;
        $this->name = $package->name;
        $this->description = $package->description;
        $this->price = $package->price;

        $this->oldImage = $package->image;

        // 🔥 ambil class yg sudah dipilih
        $this->selectedClasses = $package->classes->pluck('id')->toArray();
    }

    public function getSelectedCountProperty()
    {
        return count($this->selectedClasses ?? []);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $package = Packages::findOrFail($this->packageId);

        $imagePath = $this->oldImage;

        // 🔥 kalau upload gambar baru
        if ($this->image) {

            // hapus gambar lama
            if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                Storage::disk('public')->delete($this->oldImage);
            }

            // upload baru
            $imagePath = $this->image->store('packages', 'public');
        }

        // 🔥 update data
        $package->update([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image' => $imagePath,
        ]);

        // 🔥 sync kelas
        $package->classes()->sync($this->selectedClasses);

        session()->flash('success', 'Paket berhasil diupdate');

        return redirect()->route('packages.index');
    }

    public function render()
    {
        return view('livewire.packages.form.edit', [
            'classes' => ClassRoom::all()
        ])->layout('layouts.admin');
    }
}