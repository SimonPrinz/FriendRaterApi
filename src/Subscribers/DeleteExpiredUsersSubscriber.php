<?php

namespace App\Subscribers;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class DeleteExpiredUsersSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onRequest',
        ];
    }

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onRequest()
    {
        $query = $this->entityManager->createQueryBuilder()
            ->delete(User::class, 'user')
            ->where('user.activateUntil <= :date')
            ->getQuery();
        $query
            ->setParameter('date', new DateTime())
            ->execute();
    }
}
