<?php

namespace App\ReadModel;

use Ecotone\EventSourcing\ProjectionRunningConfiguration;
use Ecotone\Messaging\Attribute\ServiceContext;

class ReadModelConfiguration
{
    const LAST_PREPARED_TICKETS = "last_prepared_tickets";
    const UNASSIGNED_TICKETS = "unassigned_tickets";

    #[ServiceContext]
    public function getConfiguration()
    {
        return [
            ProjectionRunningConfiguration::createPolling(self::LAST_PREPARED_TICKETS),
            ProjectionRunningConfiguration::createPolling(self::UNASSIGNED_TICKETS)
        ];
    }
}