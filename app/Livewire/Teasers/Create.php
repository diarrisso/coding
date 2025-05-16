<?php

namespace App\Livewire\Teasers;

use App\Models\Teaser;
use App\Models\User;
use App\Notifications\TeaserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class Create extends Component
{
    public string $title = '';
    public string $description = '';
    public string $slug = '';
    public ?int $user_id = null;
    public string $image_name = '';

    protected function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:1000',
            'slug' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->user_id = Auth::id();
    }

    public function save()
    {
        $this->validate();

        // Ensure image_name has a default value if empty
        if (empty($this->image_name)) {
            $this->image_name = 'default-teaser-image.jpg';
        }

        $teaser = Teaser::create([
            'title' => $this->title,
            'description' => $this->description,
            'slug' => $this->slug,
            'user_id' => $this->user_id,
            'image_name' => $this->image_name,
        ]);

        // Send notification to admin users
        $admins = User::where('role', 'ADMIN')->get();
        Notification::send($admins, new TeaserNotification($teaser));

        return redirect()->route('teasers.index');
    }

    public function render()
    {
        return view('livewire.teasers.create')
            ->layout('components.layouts.app');
    }
}
