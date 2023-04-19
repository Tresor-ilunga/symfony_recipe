<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactController
 * @author Tresor-ilunga <ilungat82@gmail.com>
 */
class ContactController extends AbstractController
{
    /**
     * This method is used to display the contact form
     *
     * @throws TransportExceptionInterface
     */
    #[Route('/contact', name: 'contact.index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $manager, MailService $mailService): Response
    {
        $contact = new Contact();

        if ($this->getUser())
        {
            $contact->setFullName($this->getUser()->getFullName())
                    ->setEmail($this->getUser()->getEmail());
        }

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $contact = $form->getData();

            $manager->persist($contact);
            $manager->flush();

            // Email
            $mailService->sendEmail(
                $contact->getEmail(),
                $contact->getSubject(),
                'emails/contact.html.twig',
                ['contact' => $contact]
            );

            $this->addFlash
            (
                'success',
                'Votre message a été envoyé avec succès !'
            );

            return $this->redirectToRoute('contact.index');
        }

        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
