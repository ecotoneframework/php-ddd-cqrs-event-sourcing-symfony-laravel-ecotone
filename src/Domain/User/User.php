<?php declare(strict_types=1);

namespace App\Domain\User;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Ecotone\Modelling\Attribute\Aggregate;
use Ecotone\Modelling\Attribute\AggregateIdentifier;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ramsey\Uuid\Uuid;

#[Aggregate] // 1
#[Entity]
#[Table("users")]
class User
{
    #[AggregateIdentifier] // 2
    #[Id]
    #[Column(type: "string")]
    private string $userId;
    #[Column(type: "string")]
    private string $name;
    #[Column(type: "boolean")]
    private bool $isActive;

    private function __construct(string $userId, string $name)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->isActive = false;
    }

    #[CommandHandler("registerUser")] // 3
    public static function register(string $name): static
    {
        return new static(Uuid::uuid4()->toString(), $name);
    }

    #[CommandHandler("activateUser")] // 3
    public function activate(): void
    {
        $this->isActive = true;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }
}