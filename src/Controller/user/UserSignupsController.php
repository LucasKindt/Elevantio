<?php

namespace App\Controller\user;

use App\Repository\SponsorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

        $groupedSignups = [];

        foreach ($user->getSignups() as $signup) {
            $activityId = $signup->getActivityDate()->getActivity()->getId();
            $activityDateId = $signup->getActivityDate()->getId();
            if (!isset($groupedSignups[$activityId])) {
                $groupedSignups[$activityId][$activityDateId] = [];
            }
            $groupedSignups[$activityId][$activityDateId][] = $signup;
        }

        return $this->render('user/signups.html.twig', [
            'user' => $user,
            'signups' => $groupedSignups,
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }
}
