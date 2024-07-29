<?php

namespace App\Notification;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\User\UserInterface;

class Sender
{

    public function __construct(protected MailerInterface $mailer)
    {

    }

    public function sendNewUserNotificationToAdmin(UserInterface $user)
    {

        $message = new TemplatedEmail();
        $message->from('account@series.com')
            ->to('admin@series.com')
            ->subject('New account created on series.com !')
            ->text('')
            ->htmlTemplate('notification/newUser.html.twig')
            ->context([
                'user' => $user->getEmail(),
            ])
        ;

        $this->mailer->send($message);
//        file_put_contents('debug.txt', $user->getEmail());
    }
}