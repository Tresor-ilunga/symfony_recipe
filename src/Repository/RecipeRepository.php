<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 * @author Tresor-ilunga <ilungat82@gmail.com>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * This method allow us to save an entity
     *
     * @param Recipe $entity
     * @param bool $flush
     * @return void
     */
    public function save(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * This method allow us to remove an entity
     *
     * @param Recipe $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * This method allow us to find public recipes based on number of recipes
     *
     * @param int|null $nbRecipes
     * @return array
     */
    public function findPublicRecipe(?int $nbRecipes): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->where('r.isPublic = 1')
            ->orderBy('r.createdAt', 'DESC');


        if ($nbRecipes !== 0 || $nbRecipes !== null) {
            $queryBuilder->setMaxResults($nbRecipes);
        }

        return $queryBuilder->getQuery()
            ->getResult();
    }

}
