<?php

namespace VCComponent\Laravel\Redirector\Providers;

use Illuminate\Support\ServiceProvider;
use VCComponent\Laravel\Redirector\Repositories\RedirectRepository;
use VCComponent\Laravel\Redirector\Repositories\RedirectRepositoryEloquent;

class RedirectorServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->publishes([
            __DIR__ . '/../../app/Exceptions/Handler.php' => base_path('/app/Exceptions/Handler.php'),
            __DIR__ . '/../../config/redirect.php' => config_path('redirect.php'),

        ], 'redirector');
    }

    public function register()
    {

        $this->app->bind(RedirectRepository::class, RedirectRepositoryEloquent::class);

    }
}
