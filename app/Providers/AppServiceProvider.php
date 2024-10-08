<?php

namespace App\Providers;

use app\Services\GoogleCalendarService;
use Illuminate\Support\ServiceProvider;
use App\Models\Admin;
use App\Models\MemberCredential;
use App\Models\System;
use App\Observers\AdminObserver;
use App\Observers\VolunteerObserver;
use Illuminate\Database\Eloquent\Relations\Relation;

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
    public function boot() 
    {
        Relation::morphMap([
            'admin' => Admin::class,
            'volunteer' => MemberCredential::class,
            'system' => System::class,
        ]);
        Admin::observe(AdminObserver::class);
        MemberCredential::observe(VolunteerObserver::class);
    }
}
