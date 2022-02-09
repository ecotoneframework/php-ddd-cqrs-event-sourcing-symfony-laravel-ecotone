<?php declare(strict_types=1);

namespace App\Infrastructure;

use Ecotone\Dbal\Configuration\DbalConfiguration;
use Ecotone\Messaging\Attribute\ServiceContext;
use Ecotone\AnnotationFinder\Attribute\Environment;
class EcotoneConfiguration
{
    #[ServiceContext]
    // #[Environment(["dev", "prod"])] // If uncommented, test context is skipped
    public function registerTransactions()
    {
        return DbalConfiguration::createWithDefaults()
            ->withDoctrineORMRepositories(true);
    }

    #[ServiceContext]
    #[Environment(["test"])]
    public function registerTransactionsForTests()
    {
        return DbalConfiguration::createWithDefaults()
            ->withDoctrineORMRepositories(true);
    }
}
