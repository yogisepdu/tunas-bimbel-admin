<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Packages;

class HomeDashboard extends Component
{
    public $billing = 'monthly';
    public $packages = [];

    public function mount()
    {
        $this->loadPackages();
    }

    public function loadPackages()
    {
        $this->packages = Packages::with('classes')->get()->map(function ($package) {


            return [
                'id' => $package->id,
                'name' => $package->name,
                'description' => $package->description,
                'image' => $package->image,

                // 🔥 kalau hanya 1 price di DB
                'price_monthly' => $package->price,
                'price_yearly' => $package->price * 10, // contoh diskon tahunan

                // 🔥 ambil fitur dari relasi classes
                'features' => $package->classes->pluck('name')->toArray(),

                // 🔥 default logic
                'button' => $package->price == 0 ? 'Mulai Gratis' : 'Subscribe',

                // 🔥 highlight otomatis (misal package termahal)
                'highlight' => false,
            ];
        })->toArray();

        // 🔥 set highlight otomatis (contoh: harga tertinggi)
        $maxPrice = collect($this->packages)->max('price_monthly');

        $this->packages = collect($this->packages)->map(function ($pkg) use ($maxPrice) {
            $pkg['highlight'] = $pkg['price_monthly'] == $maxPrice;
            return $pkg;
        })->toArray();
    }

    public function buy($id)
    {
        return redirect('/checkout/' . $id);
    }

    public function render()
    {
        return view('livewire.home-dashboard')
            ->layout('layouts.landing');
    }
}