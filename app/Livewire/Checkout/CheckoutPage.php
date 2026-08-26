<?php

namespace App\Livewire\Checkout;

use App\Models\Packages;
use Livewire\Component;

class CheckoutPage extends Component
{
    public Packages $package;

    public function mount(int $id): void
    {
        $this->package = Packages::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.checkout.checkout-page')
            ->layout('layouts.landing');
    }
}
