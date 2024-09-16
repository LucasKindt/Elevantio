<?php

namespace App\Controller\user;

use App\Repository\SponsorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\User;

class UserSignupsController extends AbstractController
{

    #[Route('/gebruiker/inschrijvingen', name: 'app_user_signups')]
    public function index(
        sponsorRepository $sponsorRepository,
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        return $this->render('user/signups.html.twig', [
            'user' => $user,
            'signups' => $user->getSignups(),
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }
}