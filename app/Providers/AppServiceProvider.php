<?php

namespace App\Providers;

use app\Services\GoogleCalendarService;
use Illuminate\Support\ServiceProvider;
use Illumintae\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register():void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot():void
    {
        //
    }
}
