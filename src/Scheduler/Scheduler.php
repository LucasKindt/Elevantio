<?php

namespace App\Scheduler;

use App\Repository\ActivityRepository;
use App\Repository\SignupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Scheduler\Attribute\AsCronTask;
use App\Service\MailService;

class Scheduler
{
    private SignupRepository $signupRepository;

    private ActivityRepository $activityRepository;

    private MailService $mailService;

    private EntityManagerInterface $entityManager;

    public function __construct(
        SignupRepository $signupRepository,
        ActivityRepository $activityRepository,
        MailService  $mailService,
        EntityManagerInterface $entityManager
    ) {

        $this->signupRepository = $signupRepository;
        $this->activityRepository = $activityRepository;
        $this->mailService = $mailService;
        $this->entityManager = $entityManager;
    }
    #[AsCronTask('0 20 * * *', timezone: 'Europe/Amsterdam')]
    public function sendActivityReminder(): bool
    {
        $signups = $this->signupRepository->findAll();
        if (!empty($signups)) {
            foreach ($signups as $signup) {
                $activity = $signup->getActivity();
                $user = $signup->getUser();

                $now = new \DateTime();

                $interval = new \DateInterval('P1D');

                $twentyFourHoursFromNow = (clone $now)->add($interval);

                if ($activity->getDate() >= $now && $activity->getDate() <= $twentyFourHoursFromNow) {
                    $this->mailService->sendActivityReminderNotification($activity, $user);
                }
            }
            return true;
        }
        return false;
    }

    #[AsCronTask('*/5 * * * *', timezone: 'Europe/Amsterdam')]
    public function checkActivityDates(): void
    {
        $activities = $this->activityRepository->findAll();
        foreach($activities as $activity)
        {
            if($activity->getDate() < new \DateTime())
            {
                $this->entityManager->remove($activity);
                $this->entityManager->flush();
            }
        }
    }
}