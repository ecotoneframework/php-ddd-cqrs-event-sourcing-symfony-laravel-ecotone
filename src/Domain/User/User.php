<?php declare(strict_types=1);

namespace App\Domain\User;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Ramsey\Uuid\Uuid;

#[Entity]
#[Table("users")]
class User
{
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

    public static function register(string $name): static
    {
        return new static(Uuid::uuid4()->toString(), $name);
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }
}