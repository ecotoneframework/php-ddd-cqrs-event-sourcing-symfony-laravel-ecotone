<?php

namespace App\Infrastructure;

use Ecotone\Amqp\AmqpBackedMessageChannelBuilder;
use Ecotone\Amqp\Distribution\AmqpDistributedBusConfiguration;
use Ecotone\Messaging\Attribute\ServiceContext;

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
}
