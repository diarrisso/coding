<?php

namespace App\Livewire;

use App\Models\Teaser;
use Livewire\Component;

class TeaserShow extends Component
{

    public Teaser $teaser;

    public function mount(Teaser $teaser)
    {
        $this->teaser = $teaser;
    }

    public function render()
    {
        return view('livewire.teasers.show', [
            'teaser' => $this->teaser,
        ])->layout('components.layouts.app');
    }
}
