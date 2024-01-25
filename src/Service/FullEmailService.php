<?php

namespace App\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Twig\Environment;

class FullEmailService
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendEmail(string $recipient, string $subject, string $content): void
    {
        $email = (new Email())
            ->from('contact@makesense.com')
            ->to($recipient)
            ->subject($subject)
            ->html($content);

        $this->mailer->send($email);
    }

    public function sendEmailsToUsers(ArrayCollection $users, string $subject, string $template): void
    {
        $usersArray = $users->toArray();

        foreach ($usersArray as $user) {
            if ($user instanceof User) {
                $recipient = $user->getEmail();
                $content = $this->twig->createTemplate($template)->render(['user' => $user]);

                $this->sendEmail($recipient, $subject, $content);
            }
        }
    }
}
