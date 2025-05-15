<?php

namespace App\Livewire;

use App\Models\Teaser;
use Illuminate\View\View;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * TeaserList Component
 *
 * This component handles the listing of teasers for the authenticated user.
 */
class TeaserList extends Component
{
    /** @var \Illuminate\Database\Eloquent\Collection The collection of teasers */
    public $teasers;

    /** @var array The event listeners for this component */
    protected $listeners = [
        'teaser-updated' => '$refresh',
        'teaser-created' => '$refresh',
        'refresh-teasers' => '$refresh',
        'teaser-deleted' => '$refresh'
    ];



    public function mount(): void
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            session()->flash('error', 'Vous devez être connecté pour accéder à cette page dans mount.');
            redirect()->route('login');
            return;
        }
    }


    /**
     * Render the component.
     *
     * @return View
     */
    public function render(): View
    {
        $this->teasers = Auth::check() ? Teaser::where('user_id', Auth::id())->latest()->get() : collect();

        return view('livewire.teasers.teaser-list', [
            'teasers' => $this->teasers
        ]);
    }
}
