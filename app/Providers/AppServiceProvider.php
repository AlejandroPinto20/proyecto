<?php

namespace App\Providers;

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
        \Gate::policy(\App\Models\Certificate::class, \App\Policies\CertificatePolicy::class);
        \Gate::policy(\App\Models\Donation::class, \App\Policies\DonationPolicy::class);
        \Gate::policy(\App\Models\Ranking::class, \App\Policies\RankingPolicy::class);
        
        // Register observers
        \App\Models\Activity::observe(\App\Observers\ActivityObserver::class);
        \App\Models\Donation::observe(\App\Observers\DonationObserver::class);
        \App\Models\Certificate::observe(\App\Observers\CertificateObserver::class);
    }
}
