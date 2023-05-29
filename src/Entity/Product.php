<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="products", options={"comment":"Товар"})
 */
class Product extends AbstractBaseIdEntity
{
    /**
     * Название товара
     *
     * @ORM\Column(type="string", length=255, options={"comment":"Название товара"}, nullable=false)
     */
    private ?string $title;

    /**
     * Цена товара в копейках/центах
     *
     * @ORM\Column(type="integer", nullable=false, options={"default": 1})
     */
    private int $price = 1;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
