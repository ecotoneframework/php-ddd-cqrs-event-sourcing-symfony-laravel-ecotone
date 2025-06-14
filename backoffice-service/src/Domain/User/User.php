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

#[Aggregate]
#[Entity]
#[Table("users")]
class User
{
    #[AggregateIdentifier]
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

    #[CommandHandler("registerUser")]
    public static function register(string $name): static
    {
        return new static(Uuid::uuid4()->toString(), $name);
    }

    #[CommandHandler("activateUser")]
    public function activate(): void
    {
        $this->isActive = true;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    #[CommandHandler("deactivateUser")]
    public function deactivate(): void
    {
        $this->isActive = false;
    }
}