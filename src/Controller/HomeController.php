<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @author Tresor-ilunga <ilungat82@gmail.com>
 */
class HomeController extends AbstractController
{
    /**
     * This method is used to display the home page
     *
     * @param RecipeRepository $recipeRepository
     * @return Response
     */
    #[Route('/', name: 'home.index', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository): Response
    {
        return $this->render('pages/home.html.twig', [
            'recipes' => $recipeRepository->findPublicRecipe(3)
        ]);
    }
}