<?php

namespace App\Tests\Unit;

use App\Entity\Mark;
use App\Entity\Recipe;
use App\Entity\User;
use DateTimeImmutable;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeTest extends KernelTestCase
{
    public function getEntity(): Recipe
    {
        return (new Recipe())
                ->setName('Recipe #1')
                ->setDescription('Description #1')
                ->setIsFavorite(true)
                ->setCreatedAt(new DateTimeImmutable())
                ->setUpdateAt(new DateTimeImmutable());
    }

    /**
     * @throws Exception
     */
    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $recipe = $this->getEntity();

        $errors = $container->get('validator')->validate($recipe);
        $this->assertCount(0, $errors);
    }

    /**
     * @throws Exception
     */
    public function testInValidName()
    {
        self::bootKernel();
        $container = static::getContainer();

        $recipe = $this->getEntity();
        $recipe->setName('');

        $errors = $container->get('validator')->validate($recipe);
        $this->assertCount(2, $errors);
    }

    /**
     * @throws Exception
     */
    public function testGetAverage()
    {
        $recipe = $this->getEntity();
        $user = static::getContainer()->get('doctrine.orm.entity_manager')->find(User::class, 1);

        for ($i = 0; $i < 5; $i++)
        {
            $mark = new Mark();
            $mark->setMark(2)
                ->setUser($user)
                ->setRecipe($recipe);

            $recipe->addMark($mark);
        }
        $this->assertTrue(2.0 === $recipe->getAverage());
    }
}
