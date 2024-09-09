<?php

namespace App\Controller;

use App\Repository\SponsorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(
        sponsorRepository $sponsorRepository,
    ): Response
    {
        return $this->render('pages/home.html.twig', [
            'controller_name' => 'PageController',
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }
}
