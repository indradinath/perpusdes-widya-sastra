<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Hero_Banner;

class HeroCarousel extends Component
{
    public function render()
    {
        $banners = Hero_Banner::where('is_active', true)
                             ->orderBy('urutan', 'asc')
                             ->get();
        return view('livewire.frontend.hero-carousel', [
            'banners' => $banners
        ]);
    }
}
