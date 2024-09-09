<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Activity;
use App\Form\Filter\ActivityFilterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Spiriit\Bundle\FormFilterBundle\Filter\FilterBuilderUpdater;
use App\Repository\SponsorRepository;

class ActivityController extends AbstractController
{
    #[Route('/activiteiten', name: 'app_activities')]
    public function __invoke(
        Request $request,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $em,
        FilterBuilderUpdater $filterBuilderUpdater,
        ActivityRepository $activityRepository,
        sponsorRepository $sponsorRepository
    ): Response
    {
        $form = $formFactory->create(ActivityFilterType::class);

        $form->handleRequest($request);

        $filterBuilder = $em
            ->getRepository(Activity::class)
            ->createQueryBuilder('e');

        $filterBuilderUpdater->addFilterConditions($form, $filterBuilder);

        // now look at the DQL =)
        dump($filterBuilder->getDql());
        dump($form);

        return $this->render('activities/activities.html.twig', [
            'form' => $form,
            'activities' => $filterBuilder->getQuery()->getResult(),
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }

    #[Route('/inschrijven', name: 'app_signup')]
    public function signup()
    {

        return $this->render('activities/signup.html.twig', [
            'form' => $form,
        ]);
    }
}
