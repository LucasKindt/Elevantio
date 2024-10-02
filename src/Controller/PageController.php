<?php

namespace App\Controller;

use App\Repository\SponsorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(sponsorRepository $sponsorRepository): Response
    {
        return $this->render('pages/home.html.twig', [
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }
}
