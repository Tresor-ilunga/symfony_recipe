<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RecipeRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Recipe
 * @author Tresor-ilunga <ilungat82@gmail.com>
 */
#[UniqueEntity('name')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[Vich\Uploadable]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 50)]
    #[Assert\Length(min: 2, max: 50)]
    #[Assert\NotBlank()]
    private ?string $name;

    // // NOTE: This is not a mapped field of entity metadata, just a simple property.
    // #[Vich\UploadableField(mapping: 'recipe_images', fileNameProperty: 'imageName', size: 'imageSize')]
    // private ?File $imageFile = null;

    // #[ORM\Column(type: 'string')]
    // private ?string $imageName = null;


    #[ORM\Column(nullable: true)]
    #[Assert\Positive()]
    #[Assert\LessThan(1441)]
    private ?int $time = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive()]
    #[Assert\LessThan(51)]
    private ?int $nbPeople = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive()]
    #[Assert\LessThan(6)]
    private ?int $difficulty = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    private ?string $description;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive()]
    #[Assert\LessThan(1001)]
    private ?float $price = null;

    #[ORM\Column]
    private ?bool $isFavorite;

    #[ORM\Column]
    private ?bool $isPublic = false;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $createdAt;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?DateTimeImmutable $updatedAt;

    #[ORM\ManyToMany(targetEntity: Ingredient::class)]
    private Collection $ingredients;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    private ?float $average = null;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Mark::class, orphanRemoval: true)]
    private Collection $marks;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->createdAt = new  DateTimeImmutable;
        $this->updatedAt = new DateTimeImmutable();
        $this->marks = new ArrayCollection();
    }

    #[ORM\PrePersist()]
    public function setUpdateAtValue(): void
    {
        $this->updatedAt = new DateTimeImmutable();
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


    // /**
    //  * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
    //  * of 'UploadedFile' is injected into this setter to trigger the update. If this
    //  * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
    //  * must be able to accept an instance of 'File' as the bundle will inject one here
    //  * during Doctrine hydration.
    //  *
    //  * @param File|null $imageFile
    //  */
    // public function setImageFile(?File $imageFile = null): void
    // {
    //     $this->imageFile = $imageFile;

    //     if (null !== $imageFile) {
    //         // It is required that at least one field changes if you are using doctrine
    //         // otherwise the event listeners won't be called and the file is lost
    //         $this->updatedAt = new DateTimeImmutable();
    //     }
    // }

    // public function getImageFile(): ?File
    // {
    //     return $this->imageFile;
    // }

    // public function setImageName(?string $imageName): void
    // {
    //     $this->imageName = $imageName;
    // }

    // public function getImageName(): ?string
    // {
    //     return $this->imageName;
    // }


    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(?int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getNbPeople(): ?int
    {
        return $this->nbPeople;
    }

    public function setNbPeople(?int $nbPeople): self
    {
        $this->nbPeople = $nbPeople;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(?int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function isIsFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setIsFavorite(bool $isFavorite): self
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

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

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdateAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Mark>
     */
    public function getMarks(): Collection
    {
        return $this->marks;
    }

    public function addMark(Mark $mark): self
    {
        if (!$this->marks->contains($mark)) {
            $this->marks->add($mark);
            $mark->setRecipe($this);
        }

        return $this;
    }

    public function removeMark(Mark $mark): self
    {
        if ($this->marks->removeElement($mark)) {
            // set the owning side to null (unless already changed)
            if ($mark->getRecipe() === $this) {
                $mark->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return float|null
     */
    public function getAverage(): ?float
    {
        $marks = $this->marks;
        if ($marks->toArray() ===  [])
        {
            $this->average = null;
            return $this->average;
        }
        $total = 0;
        foreach ($marks as $mark)
        {
            $total += $mark->getMark();
        }
        $this->average = $total / count($marks);

        return $this->average;
    }

    /**
     * @param float|null $average
     */
    public function setAverage(?float $average): void
    {
        $this->average = $average;
    }


}
