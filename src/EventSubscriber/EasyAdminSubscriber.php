<?php
# src/EventSubscriber/EasyAdminSubscriber.php
namespace App\EventSubscriber;

use App\Entity\Order;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private RequestStack $requestStack,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['BeforeEntityPersistedEvent'],
            BeforeEntityDeletedEvent::class => ['BeforeEntityDeletedEvent'],
            BeforeEntityUpdatedEvent::class => ['BeforeEntityUpdatedEvent'],
        ];
    }

    #[NoReturn] public function BeforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event): void
    {
        $session = $this->requestStack->getSession();

        // Get entity from event
        $entity = $event->getEntityInstance();
        // Check if entity is user
        if ($entity instanceof User) {
            // Check if password update is required
            if($entity->getNewPassword() !== '' ) {
                // Encode form NewPassword
                $encodedPassword = $this->hasher->hashPassword($entity, $entity->getNewPassword());
                // Set new user password
                $entity->setPassword($encodedPassword);
            }
        }

        // Check if entity is order
        if ($entity instanceof Order) {
            foreach($entity->getOrderProducts() as $orderProduct)
            {
                if(!$orderProduct->isStockUpdated())
                {
                    $product = $orderProduct->getProduct();
                    if($product->getStock() >= $orderProduct->getAmount())
                    {
                        $product->setStock($product->getStock() - $orderProduct->getAmount());
                        $orderProduct->setStockUpdated(true);
                    }
                    else
                    {
                        $session->getFlashBag()->add('warning', 'Product niet op voorraad!');
                        $entity->removeOrderProduct($orderProduct);
                    }
                }
            }
        }
    }

    #[NoReturn] public function BeforeEntityPersistedEvent(BeforeEntityPersistedEvent $event): void
    {
        $session = $this->requestStack->getSession();

        // Get entity from event
        $entity = $event->getEntityInstance();
        // Check if entity is order
        if ($entity instanceof Order) {
            foreach($entity->getOrderProducts() as $orderProduct)
            {
                if(!$orderProduct->isStockUpdated())
                {
                    $product = $orderProduct->getProduct();
                    if($product->getStock() >= $orderProduct->getAmount())
                    {
                        $product->setStock($product->getStock() - $orderProduct->getAmount());
                        $orderProduct->setStockUpdated(true);
                    }
                    else
                    {
                        $session->getFlashBag()->add('warning', 'Product niet op voorraad!');
                        $entity->removeOrderProduct($orderProduct);
                    }
                }
            }
        }
    }

    #[NoReturn] public function BeforeEntityDeletedEvent(BeforeEntityDeletedEvent $event): void
    {
        // Get entity from event
        $entity = $event->getEntityInstance();
    }
}