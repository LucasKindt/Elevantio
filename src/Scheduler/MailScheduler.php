<?php

namespace App\Scheduler;

use App\Repository\SignupRepository;
use Symfony\Component\Scheduler\Attribute\AsCronTask;
use App\Service\MailService;

class MailScheduler
{
    private SignupRepository $signupRepository;

    private MailService $mailService;

    public function __construct(
        SignupRepository $SignupRepository,
        MailService  $MailService
    ) {

        $this->signupRepository = $SignupRepository;
        $this->mailService = $MailService;
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
}