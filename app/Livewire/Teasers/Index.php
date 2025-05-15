<?php

namespace App\Livewire\Teasers;

use App\Models\Teaser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.teasers.index')->layout('components.layouts.app');
    }
}
