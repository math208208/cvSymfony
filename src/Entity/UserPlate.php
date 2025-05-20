<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_plate')]
class UserPlate
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private int $userId;

    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $field;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private int $i;

    #[ORM\Column(type: 'text')]
    private string $value;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): void
    {
        $this->field = $field;
    }

    public function getI(): int
    {
        return $this->i;
    }

    public function setI(int $i): void
    {
        $this->i = $i;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
