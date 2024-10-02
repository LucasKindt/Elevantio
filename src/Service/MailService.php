<?php
namespace App\Service;

use App\Entity\Activity;
use App\Entity\Signup;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendActivityReminderNotification(Signup $signup, User $user): Bool
    {
        $email = (new TemplatedEmail())
            ->from(new Address('lucaskindt77@gmail.com', 'De Rijke Schooldag'))
            ->to($user->getEmail()) // Replace with dynamic user email
            ->subject('Activiteit herinnering')
            ->htmlTemplate('mail/reminder_email.html.twig')
            ->context(['signup' => $signup]);

        try {
            $this->mailer->send($email);
            return true;
        } catch (TransportExceptionInterface $e) {
            return false;
        }
    }

    public function sendActivityChangedNotification(Signup $signup, User $user): Bool
    {
        $email = (new TemplatedEmail())
            ->from(new Address('lucaskindt77@gmail.com', 'De Rijke Schooldag'))
            ->to($user->getEmail()) // Replace with dynamic user email
            ->subject('Activiteit gewijzigd')
            ->htmlTemplate('mail/activitychange_email.html.twig')
            ->context(['signup' => $signup,]);

        try {
            $this->mailer->send($email);
            return true;
        } catch (TransportExceptionInterface $e) {
            return false;
        }
    }

    public function sendContactForm(FormInterface $form): Bool
    {
        $email = (new TemplatedEmail())
            ->from(new Address('lucaskindt77@gmail.com', 'De Rijke Schooldag'))
            ->to(new Address('lucaskindt77@gmail.com', 'De Rijke Schooldag'))
            ->subject('Contact Formulier')
            ->htmlTemplate('mail/contactform_email.html.twig')
            ->context(['form' => $form->getData(),]);

        try {
            $this->mailer->send($email);
            return true;
        } catch (TransportExceptionInterface $e) {
            return false;
        }
    }
}