<?php

namespace App\Livewire;

use App\Models\Packages;
use Livewire\Component;

class HomeDashboard extends Component
{
    public string $billing = 'monthly';

    public array $packages = [];

    public function mount(): void
    {
        $this->loadPackages();
    }

    public function loadPackages(): void
    {
        $this->packages = Packages::with('classes')
            ->get()
            ->map(function ($package) {
                return [
                    'id' => $package->id,
                    'name' => $package->name,
                    'description' => $package->description,
                    'image' => $package->image,
                    'price_monthly' => (float) $package->price,
                    'price_yearly' => (float) $package->price * 10,
                    'features' => $package->classes
                        ->pluck('name')
                        ->toArray(),
                    'button' => (float) $package->price === 0.0
                        ? 'Mulai Gratis'
                        : 'Pilih Paket',
                    'highlight' => false,
                ];
            })
            ->toArray();

        $maxPrice = collect($this->packages)
            ->max('price_monthly');

        $this->packages = collect($this->packages)
            ->map(function ($package) use ($maxPrice) {
                $package['highlight'] =
                    $package['price_monthly'] == $maxPrice;

                return $package;
            })
            ->toArray();
    }

    public function buy($id)
    {
        $billing = in_array(
            $this->billing,
            ['monthly', 'yearly'],
            true
        )
            ? $this->billing
            : 'monthly';

        return redirect()->route(
            'checkout',
            [
                'id' => $id,
                'billing' => $billing,
            ]
        );
    }

    public function render()
    {
        return view('livewire.home-dashboard')
            ->layout('layouts.landing');
    }
}
