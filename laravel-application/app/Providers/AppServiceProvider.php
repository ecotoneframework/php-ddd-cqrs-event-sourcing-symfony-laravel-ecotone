<?php

namespace App\Providers;

use Enqueue\AmqpExt\AmqpConnectionFactory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AmqpConnectionFactory::class, function () {
            return new AmqpConnectionFactory("amqp+lib://guest:guest@rabbitmq:5672//");
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
