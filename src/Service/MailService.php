<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;


/**
 * Class MailService
 * @author Tresor-ilunga <ilungat82@gmail.com>
 */
class MailService
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * MailService constructor.
     *
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * This method is used to send an email
     *
     * @throws TransportExceptionInterface
     */
    public function sendEmail(string $from, string $subject, string $htmlTemplate, array $context, string $to = 'admin@recipe.com'): void
    {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($htmlTemplate)
            ->context($context);

        $this->mailer->send($email);
    }
}