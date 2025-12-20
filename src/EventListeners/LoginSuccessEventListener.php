<?php

declare(strict_types=1);

namespace App\EventListeners;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mercure\Authorization;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

final class LoginSuccessEventListener implements EventSubscriberInterface
{
    public function __construct(private readonly Authorization $authorization)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'setMercureCookie',
        ];
    }

    public function setMercureCookie(LoginSuccessEvent $event): void
    {
        $this->authorization->setCookie($event->getRequest(), $event->getUser()->getUserIdentifier());
    }
}
