<?php

namespace App\Providers;

use Ecotone\Dbal\DbalConnection;
use Ecotone\OpenTelemetry\Support\JaegerTracer;
use Enqueue\AmqpExt\AmqpConnectionFactory;
use Enqueue\Dbal\DbalConnectionFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Trace\TracerProviderInterface;

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
        $this->app->singleton(DbalConnectionFactory::class, function () {
            return DbalConnection::create(DB::connection()->getDoctrineConnection());
        });
        $this->app->singleton(TracerProviderInterface::class, function () {
            return Globals::tracerProvider();
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
