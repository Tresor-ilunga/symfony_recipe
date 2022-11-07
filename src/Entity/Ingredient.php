<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 50)]
    #[Assert\Length(min: 2, max: 50)]
    #[Assert\NotBlank()]
    private ?string $name;

    #[ORM\Column]
    #[Assert\Positive()]
    #[Assert\NotNull()]
    #[Assert\LessThan(200)]
    private ?float $price;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $createdAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new  DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
