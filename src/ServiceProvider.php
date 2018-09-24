<?php

namespace Wcactus\TypographRu;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
		$this->publishes([
            __DIR__.'/../config/typographru.php' => config_path('typographru.php'),
        ], 'typographru');

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('TypographRu', function ($app) {
            return new TypographRu;
        });
    }
}
