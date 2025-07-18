<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\DatabasePresenceVerifier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }


    public function boot() {
        if (isset($this->app['db'])) {
            $presenceVerifier = new DatabasePresenceVerifier($this->app['db']);
            Validator::setPresenceVerifier($presenceVerifier);
        }
    }
}
