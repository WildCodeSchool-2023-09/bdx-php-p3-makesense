<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\User;

class FullEmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $recipient, string $subject, string $content): void
    {
        $email = (new Email())
            ->from('contact@makesense.com')
            ->to($recipient)
            ->subject($subject)
            ->text($content);

        $this->mailer->send($email);
    }

    public function sendEmailsToUsers(array $users, string $subject, string $content): void
    {
        foreach ($users as $user) {
            if ($user instanceof User) {
                $this->sendEmail($user->getEmail(), $subject, $content);
            }
        }
    }
}
