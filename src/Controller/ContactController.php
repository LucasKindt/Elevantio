<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Form\UserSettingsType;
use App\Repository\SponsorRepository;
use App\Service\MailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(sponsorRepository $sponsorRepository, Request $request, MailService  $mailService): Response
    {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $mailService->sendContactForm($form);
        }

        return $this->render('pages/contact.html.twig', [
            'sponsors' => $sponsorRepository->findAll(),
            'form' => $form
        ]);
    }
}
