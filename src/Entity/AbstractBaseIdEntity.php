<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractBaseIdEntity
{
    use CreatedAtTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="bigint")
     */
    protected int $id;

    public function getId(): int
    {
        return $this->id;
    }
}
