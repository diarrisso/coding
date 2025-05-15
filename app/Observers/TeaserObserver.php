<?php

namespace App\Observers;

use App\Models\Teaser;

class TeaserObserver
{
    /**
     * Handle the Teaser "created" event.
     *
     * @param Teaser $teaser
     * @return void
     */
    public function created(Teaser $teaser)
    {

    }

    /**
     * Handle the Teaser "updated" event.
     *
     * @param Teaser $teaser
     * @return void
     */
    public function updated(Teaser $teaser)
    {
        //
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
