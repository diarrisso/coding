<?php

namespace App\Livewire;

use App\Models\Teaser;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HomeTeasers  extends Component
{
    public $initialTeaserCount = 10;
    public $addMoreTeaser= 5;
    public $currentCount = 10;
    public $hasMoreTeasers = false;
    public $totalCount = 0;


    public function mount()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Sie müssen angemeldet sein, um auf diese Seite zuzugreifen.');
            redirect()->route('login');
            return;
        }

        $this->totalCount = Teaser::count();
        $this->hasMoreTeasers = $this->totalCount > $this->initialTeaserCount;
        $this->currentCount = $this->initialTeaserCount;
    }


    public function loadMoreTeasers(): void
    {
        $this->currentCount += $this->addMoreTeaser;
        $this->hasMoreTeasers = $this->totalCount > $this->currentCount;
    }


    public function render()
    {
        $teasers = Teaser::latest()->take($this->currentCount)->get();
        return view('livewire.teasers.homeTeasers', [
            'user' =>Auth::user(),
            'teasers' =>$teasers,
        ])
            ->layout('components.layouts.app');
    }
}
