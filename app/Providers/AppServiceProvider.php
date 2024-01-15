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
        $this->app->bind(\App\Repositories\Dokter\DokterInterface::class, \App\Repositories\Dokter\DokterRepository::class);
        $this->app->bind(\App\Repositories\Poli\PoliInterface::class, \App\Repositories\Poli\PoliRepository::class);
        $this->app->bind(\App\Repositories\Penjamin\PenjaminInterface::class, \App\Repositories\Penjamin\PenjaminRepository::class);
        $this->app->bind(\App\Repositories\Penjamin\PenjaminInterface::class, \App\Repositories\Penjamin\PenjaminRepository::class);
        $this->app->bind(\App\Repositories\Pasien\PasienInterface::class, \App\Repositories\Pasien\PasienRepository::class);
        $this->app->bind(\App\Repositories\Tanggal\TanggalInterface::class, \App\Repositories\Tanggal\TanggalRepository::class);
        $this->app->bind(\App\Repositories\Antrian\AntrianInterface::class, \App\Repositories\Antrian\AntrianRepository::class);
        $this->app->bind(\App\Repositories\Auth\AuthInterface::class, \App\Repositories\Auth\AuthRepository::class);
        $this->app->bind(\App\Repositories\Antrol\AntrolInterface::class, \App\Repositories\Antrol\AntrolRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
