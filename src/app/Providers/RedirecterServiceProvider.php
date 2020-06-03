<?php

namespace VCComponent\Laravel\Redirecter\Providers;

use Illuminate\Support\ServiceProvider;
use VCComponent\Laravel\Redirecter\Repositories\RedirectRepository;
use VCComponent\Laravel\Redirecter\Repositories\RedirectRepositoryEloquent;

class RedirecterServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->publishes([
            __DIR__ . '/../../app/Exceptions/Handler.php' => base_path('/app/Exceptions/Handler.php'),

        ], 'ridereccter');
    }

    public function register()
    {

        $this->app->bind(RedirectRepository::class, RedirectRepositoryEloquent::class);

    }
}
