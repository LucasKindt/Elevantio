<?php

namespace App\Controller;

use App\Entity\ActivityDate;
use App\Entity\Child;
use App\Entity\Signup;
use App\Entity\Sponsor;
use App\Form\SignupType;
use App\Repository\ActivityRepository;
use App\Repository\SignupRepository;
use DateTime;
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
        Request                $request,
        FormFactoryInterface   $formFactory,
        EntityManagerInterface $em,
        FilterBuilderUpdater   $filterBuilderUpdater,
        ActivityRepository     $activityRepository,
        sponsorRepository      $sponsorRepository,
    ): Response
    {
        $form = $formFactory->create(ActivityFilterType::class);

        $form->handleRequest($request);

        $filterBuilder = $em
            ->getRepository(Activity::class)
            ->createQueryBuilder('e');

        $filterBuilderUpdater->addFilterConditions($form, $filterBuilder);

        return $this->render('activities/activities.html.twig', [
            'form' => $form,
            'activities' => $filterBuilder->getQuery()->getResult(),
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }

    #[Route('/activiteiten/{id}/inschrijven', name: 'app_signup')]
    public function signup(Request $request, EntityManagerInterface $entityManager, Activity $activity): Response
    {
        $user = $this->getUser(); // Get the logged-in user

        // Initialize the form with a new Signup object
        $form = $this->createForm(SignupType::class, null, [
            'user' => $user, // Pass the user to the form
            'activity' => $activity,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the selected child and activity dates
            $activityDates = $form->get('activityDate')->getData();
            $succes = false;
            // For each selected activity date, create a new signup entry
            foreach ($activityDates as $activityDate) {
                if ($entityManager->getRepository(Signup::class)->findOneBy(['child' => $form->get('child')->getData(), 'activityDate' => $activityDate,]) === null) {
                    $newSignup = new Signup();
                    $newSignup->setChild($form->get('child')->getData());
                    $newSignup->setActivityDate($activityDate);
                    $newSignup->setSignedUpAt(new DateTime());
                    $newSignup->setUser($user);

                    $entityManager->persist($newSignup);

                    $succes = true;
                }
                else
                {
                    $this->addFlash(
                        'warning',
                        'Een kind is al ingeschreven voor deze activiteit.'
                    );
                }
            }

            if ($succes) {
                $this->addFlash(
                    'notice',
                    'Aanmelding(en) voltooid.'
                );
            } else {
                $this->addFlash(
                    'warning',
                    'Aanmelding(en) niet voltooid.'
                );
            }

            $entityManager->flush();


            return $this->redirectToRoute('app_home');
        }

        return $this->render('activities/signup.html.twig', [
            'form' => $form->createView(),
            'activity' => $activity,
            'sponsors' => $entityManager->getRepository(Sponsor::class)->findAll(),
        ]);
    }

    #[Route('/activiteiten/{id}/cancel', name: 'activity_cancel')]
    public function cancelSignup(EntityManagerInterface $entityManager, Signup $signup): Response
    {
        $entityManager->remove($signup);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Afmelding(en) voltooid.'
        );

        return $this->redirectToRoute('app_user_signups', ['id' => $signup->getActivityDate()->getActivity()->getId()]);
    }
}
