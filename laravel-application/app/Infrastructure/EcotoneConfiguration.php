<?php

namespace App\Infrastructure;

use Ecotone\Amqp\AmqpBackedMessageChannelBuilder;
use Ecotone\Amqp\Distribution\AmqpDistributedBusConfiguration;
use Ecotone\Messaging\Attribute\ServiceContext;
use Ecotone\OpenTelemetry\Configuration\TracingConfiguration;

class EcotoneConfiguration
{
    const NOTIFICATIONS_CHANNEL = "notifications";

    #[ServiceContext]
    public function asynchronousNotifications()
    {
        return AmqpBackedMessageChannelBuilder::create(self::NOTIFICATIONS_CHANNEL);
    }

    #[ServiceContext]
    public function distributedPublisher()
    {
        return [
            AmqpDistributedBusConfiguration::createConsumer(),
            AmqpDistributedBusConfiguration::createPublisher()
        ];
    }

    /**
     * We can leverage usage of auto-instrumentation
     */
    #[ServiceContext]
    public function doNotEnableTracingOnCommandBus()
    {
        return TracingConfiguration::createWithDefaults()
                    ->withForceFlushOnBusExecution(false);
    }
}
