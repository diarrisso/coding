<?php

namespace App\Livewire;

use App\Models\Teaser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class TeaserDelete extends Component
{
    public bool $showDeleteModal = false;

    public ?int $teaserId = null;

    protected $listeners = [
        'confirmDeleteTeaser' => 'confirmDelete',
    ];


    public function confirmDelete(int $teaserId): void
    {
        $this->teaserId = $teaserId;
        $this->showDeleteModal = true;
    }


    public function cancelDelete(): void
    {
        $this->reset(['teaserId', 'showDeleteModal']);
    }

    private function deleteImage(Teaser $teaser): void
    {
        if ($teaser->image_name) {
            $path = 'teasers/' . $teaser->id . '/' . $teaser->image_name;
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                Log::info('Deleted teaser image', ['path' => $path]);
            }
        }
    }


    public function deleteTeaser(): void
    {


        try {
            if (!Auth::check()) {
                session()->flash('error', 'Sie müssen angemeldet sein, um auf diese Funktion zuzugreifen.');
                $this->redirect(route('teasers.index'));
                return;
            }
            $teaser = Teaser::find($this->teaserId);


            $this->authorize('delete', $teaser);

            if (!$teaser) {
                session()->flash('error', 'Der angeforderte Teaser existiert nicht.');
                $this->showDeleteModal = false;
                $this->redirect(route('teasers.index'));
                return;
            }

            if ($teaser->user_id !== Auth::id()) {
                session()->flash('error', 'Sie sind nicht berechtigt, diesen Teaser zu löschen.');
                $this->showDeleteModal = false;
                $this->redirect(route('teasers.index'));
                return;
            }

            $this->deleteImage($teaser);
            $teaser->delete();

            $this->showDeleteModal = false;

            $this->dispatch('teaser-deleted');
            $this->dispatch('refresh-teasers');


            session()->flash('message', 'Teaser erfolgreich gelöscht.');
            $this->redirect(route('teasers.index'));

        } catch (\Exception $e) {

            session()->flash('error', 'Beim Löschen des Teasers ist ein Fehler aufgetreten: ' . $e->getMessage());
            $this->redirect(route('teasers.index'));
        }
    }


    public function render()
    {
        return view('livewire.teasers.teaser-delete');
    }
}
