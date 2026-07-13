<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use File;

class PluginServiceProvider extends ServiceProvider
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
        $pluginsPath = app_path('Plugins');

        if (!File::exists($pluginsPath)) {
            return;
        }

        // Dapatkan seluruh sub-direktori di bawah app/Plugins
        $pluginDirs = File::directories($pluginsPath);

        foreach ($pluginDirs as $dir) {
            $pluginName = basename($dir);

            // 1. Load bootstrap/init.php jika ada
            $initPath = $dir . '/init.php';
            if (File::exists($initPath)) {
                require_once $initPath;
            }

            // 2. Load Routes dinamis dari plugin (jika ada routes.php)
            $routesPath = $dir . '/routes.php';
            if (File::exists($routesPath)) {
                Route::middleware('web')
                    ->group($routesPath);
            }

            // 3. Load Views dinamis dari plugin (jika ada folder views)
            $viewsPath = $dir . '/views';
            if (File::exists($viewsPath)) {
                $this->loadViewsFrom($viewsPath, $pluginName);
            }

            // 4. Load Migrations dinamis dari plugin (jika ada folder migrations)
            $migrationsPath = $dir . '/migrations';
            if (File::exists($migrationsPath)) {
                $this->loadMigrationsFrom($migrationsPath);
            }
        }
    }
}
