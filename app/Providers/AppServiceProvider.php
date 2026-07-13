<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Menu;

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
        // Bagikan data menu navigasi aktif secara global ke semua template OPAC
        View::composer('opac.templates.*', function ($view) {
            $view->with('opacMenus', Menu::active()->get());
        });
    }
}
