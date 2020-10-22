<?php

namespace App\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class EmailSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            MessageEvent::class => 'onMessage',
        ];
    }

    public function onMessage(MessageEvent $event)
    {
        if (!($event->getMessage() instanceof Email)) {
            return;
        }

        /** @var Email $email */
        $email = $event->getMessage();

        $email->from(Address::create('FriendRater <friendrater@simonprinz.me>'));
        $email->subject((($email->getSubject() == null || empty($email->getSubject())) ? '' : $email->getSubject() . ' - ') . 'FriendRater');
    }
}
