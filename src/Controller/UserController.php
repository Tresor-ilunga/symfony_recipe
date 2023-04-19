<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class UserController
 * @author Tresor-ilunga <ilungat82@gmail.com>
 */
class UserController extends AbstractController
{

    /**
     * This function allow us to edit user's profile
     *
     * @param User $chooseUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Route('/utilisateur/edition/{id}', name: 'user.edit', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and user === chooseUser")]
    public function edit(User $chooseUser ,Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(UserType::class, $chooseUser);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            if ($hasher->isPasswordValid($chooseUser, $form->getData()->getPlainPassword()))
            {
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();

                $this->addFlash
                (
                    'success',
                    'Les informations de votre compte ont bien été modifiées.'
                );
                return  $this->redirectToRoute('recipe.index');
            }
            else
            {
                $this->addFlash
                (
                    'danger',
                    'Le mot de passe est incorrect.'
                );
            }
        }
        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * This function allow us to edit user's password
     *
     * @param User $chooseUser
     * @param Request $request
     * @param UserPasswordHasherInterface $hasher
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/utilisateur/edition-mot-de-passe/{id}', name: 'user.edit.password', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and user === chooseUser")]
    public function editPassword(User $chooseUser, Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            if ($hasher->isPasswordValid($chooseUser, $form->getData()['plainPassword']))
            {
                $chooseUser->setPassword(
                    $hasher->hashPassword(
                        $chooseUser,
                        $form->getData()['newPassword']
                    )
                );

                $manager->persist($chooseUser);
                $manager->flush();

                $this->addFlash
                (
                    'success',
                    'Le mot de passe a été modifiée.'
                );

                return $this->redirectToRoute('recipe.index');
            }
            else
            {
                $this->addFlash
                (
                    'danger',
                    'Le mot de passe est incorrect.'
                );
            }
        }

        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
