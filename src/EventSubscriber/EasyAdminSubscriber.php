<?php
# src/EventSubscriber/EasyAdminSubscriber.php
namespace App\EventSubscriber;

use App\Entity\Activity;
use App\Service\MailService;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityBuiltEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
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
            AfterEntityDeletedEvent::class => ['AfterEntityDeleted'],
            AfterEntityBuiltEvent::class => ['AfterEntityBuilt'],
        ];
    }

    public function AfterEntityDeleted(AfterEntityDeletedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Activity) {
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
        else
        {
            return;
        }
    }

    public function AfterEntityBuilt(AfterEntityBuiltEvent $event): void
    {
//        $entity = $event->getEntity();
//
//        if ($entity instanceof Activity) {
//            $entity->setCreator($this->getUser());
//            $this->entityManager->persist($entity);
//            $this->entityManager->flush();
//        }
//        else
//        {
//            return;
//        }
    }
}