<?php

namespace Vladmeh\RabbitMQ;

use Illuminate\Support\ServiceProvider;

class RabbitMQClientProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/rabbit.php',
            'rabbit'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('Rabbit', Rabbit::class);

        $this->publishes([
            __DIR__.'/../config/rabbit.php' => config_path('rabbit.php'),
        ], 'rabbit');
    }
}
