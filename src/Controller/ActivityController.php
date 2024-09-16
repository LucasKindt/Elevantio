<?php

namespace App\Controller;

use App\Entity\Signup;
use App\Form\SignupType;
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

        $filterBuilderUpdater->addFilterConditions($form, $filterBuilder);;

        return $this->render('activities/activities.html.twig', [
            'form' => $form,
            'activities' => $filterBuilder->getQuery()->getResult(),
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }

    #[Route('/activity/{id}/inschrijven', name: 'app_signup')]
    public function signup(Request $request, EntityManagerInterface $entityManager, Activity $activity, SponsorRepository $sponsorRepository): Response
    {
        $signup = new Signup();

        $user = $this->getUser();

        $form = $this->createForm(SignupType::class, null, ['children' => $user->getChildren()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->get('children')->getData() as $child) {
                $signupEntity = new Signup();
                $signupEntity->setChild($child); // Single child per signup
                $signupEntity->setUser($user);
                $signupEntity->setActivity($activity);
                $signupEntity->setSignedUpAt(new \DateTime());
                $entityManager->persist($signupEntity);
            }
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Aanmelding(en) voltooid.'
            );

            return $this->redirectToRoute('app_activities', ['id' => $activity->getId()]);
        }

        return $this->render('activities/signup.html.twig', [
            'form' => $form->createView(),
            'activity' => $activity,
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }

    #[Route('/activity/{id}/cancel', name: 'activity_cancel')]
    public function cancelSignup(Request $request, EntityManagerInterface $entityManager, Signup $signup): Response
    {
        $entityManager->remove($signup);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Afmelding(en) voltooid.'
        );

        return $this->redirectToRoute('app_activities', ['id' => $signup->getActivity()->getId()]);
    }
}
