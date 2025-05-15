<?php

namespace App\Providers;

use App\Models\Teaser;
use App\Observers\TeaserObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Automatically eager load relationships on all models.
         * This is useful for performance optimization.
         */
        Model::automaticallyEagerLoadRelationships();


        Teaser::observe(TeaserObserver::class);


        Password::defaults(static function () {
            return Password::min(8)
                ->mixedCase()
                ->numbers()
                ->uncompromised();
        });

    }
}
