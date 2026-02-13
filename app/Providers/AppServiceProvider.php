<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Penerimaan;
use App\Models\Pengiriman;
use App\Models\Perbaikan;
use App\Observers\PenerimaanObserver;
use App\Observers\PengirimanObserver;
use App\Observers\PerbaikanObserver;

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
        Penerimaan::observe(PenerimaanObserver::class);
        Perbaikan::observe(PerbaikanObserver::class);
        Pengiriman::observe(PengirimanObserver::class);
    }
}
