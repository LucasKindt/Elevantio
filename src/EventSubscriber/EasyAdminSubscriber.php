<?php
# src/EventSubscriber/EasyAdminSubscriber.php
namespace App\EventSubscriber;

use App\Entity\Activity;
use App\Service\MailService;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private MailService $mailService;

    public function __construct(MailService $mailerService)
    {
        $this->mailService = $mailerService;
    }

    public static function getSubscribedEvents():  array
    {
        return [
            AfterEntityDeletedEvent::class => ['sendActivityChanged'],
        ];
    }

    public function sendActivityChanged(AfterEntityDeletedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Activity)) {
            return;
        }

        $signups = $entity->getSignups();

        if(!empty($signups))
        {
            foreach ($signups as $signup) {
                $signup->setActivity(null);
                $user = $signup->getUser();
                $this->mailService->sendActivityChangedNotification($entity, $user);
            }
        }
    }
}