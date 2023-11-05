<?php declare(strict_types=1);

namespace App\Infrastructure;

use Ecotone\Amqp\Distribution\AmqpDistributedBusConfiguration;
use Ecotone\Dbal\Configuration\DbalConfiguration;
use Ecotone\Messaging\Attribute\ServiceContext;
use Ecotone\OpenTelemetry\Configuration\TracingConfiguration;

class EcotoneConfiguration
{
    #[ServiceContext]
    public function getDbalConfiguration(): DbalConfiguration
    {
        return DbalConfiguration::createWithDefaults()
            ->withDoctrineORMRepositories(true);
    }

    #[ServiceContext]
    public function distributedConsumer()
    {
        return AmqpDistributedBusConfiguration::createConsumer();
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