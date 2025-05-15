<?php

namespace App\Livewire\Teasers;

use Livewire\Component;

class Show extends Component
{
    public function render()
    {
        return view('livewire.teasers.show')
            ->layout('components.layouts.app');
    }
}
