<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    /**
     * This function display all recipes
     *
     * @param RecipeRepository $repository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/recette', name: 'recipe.index', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function index(RecipeRepository $repository, Request $request ,PaginatorInterface $paginator): Response
    {
        $recipes = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    /**
     * This function allow us to create a new recipe
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recette/creation', name: 'recipe.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $recipe = $form->getData();
            $recipe->setUser($this->getUser());

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash
            (
                'success',
                'Votre recette a été créé avec succès !'
            );

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('pages/recipe/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * This function allow us to edit a recipe
     *
     * @param Recipe $recipe
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recette/edition/{id}', name: 'recipe.edit', methods: ['GET', 'POST'])]
    #[Security("is_granted(ROLE_USER) and === recipe.getUser()")]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $recipe = $form->getData();

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash
            (
                'success',
                'Votre recette a été modifié avec succès !'
            );

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/recipe/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * This function allow us to delete a recipe
     *
     * @param EntityManagerInterface $manager
     * @param Recipe $recipe
     * @return Response
     */
    #[Route('recette/suppression/{id}', name: 'recipe.delete', methods: ['GET', 'POST'])]
    public function delete(EntityManagerInterface $manager, Recipe $recipe): Response
    {
        if (!$recipe)
        {
            $this->addFlash
            (
                'danger',
                'La recette n\'a pas été trouvé !'
            );
            return $this->redirectToRoute('ingredient.index');
        }

        $manager->remove($recipe);
        $manager->flush();

        $this->addFlash
        (
            'success',
            'Votre recette a été supprimé avec succès !'
        );

        return $this->redirectToRoute('ingredient.index');
    }
}
