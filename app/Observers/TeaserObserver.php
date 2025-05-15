<?php

namespace App\Observers;

use App\Models\Teaser;
use App\Models\User;
use App\Notifications\TeaserNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class TeaserObserver
{
    /**
     * Handle the Teaser "created" event.
     *
     * @param Teaser $teaser
     * @return void
     */
    public function created(Teaser $teaser): void
    {

        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new TeaserNotification($teaser));
            Log::info('TeaserObserver send ', ['count' => $admins->count()]);
        } else {
            Log::info('TeaserObserver no admins found');
        }


        $adminEmail = 'diarrisso49@gmail.com';
        Notification::route('mail', $adminEmail)->notify(new TeaserNotification($teaser));

        Log::info('TeaserObserver directly  send ', ['email' => $adminEmail]);

        Log::info('Teaser  created via TeaserObserver', ['id' => $teaser->id]);

    }

    /**
     * Handle the Teaser "updated" event.
     *
     * @param Teaser $teaser
     * @return void
     */
    public function updated(Teaser $teaser)
    {
    }

    /**
     * Handle the Teaser "deleted" event.
     *
     * @param Teaser $teaser
     * @return void
     */
    public function deleted(Teaser $teaser)
    {
        //
    }

}
