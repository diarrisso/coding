<?php

namespace App\Policies;

use App\Models\Teaser;
use App\Models\User;

class TeaserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Teaser $teaser): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Teaser $teaser): bool
    {
        return $user->id === $teaser->user_id;

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Teaser $teaser): bool
    {
        return $user->id === $teaser->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Teaser $teaser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Teaser $teaser): bool
    {
        return false;
    }
}
