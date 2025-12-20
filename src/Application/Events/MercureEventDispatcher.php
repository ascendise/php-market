<?php

declare(strict_types=1);

namespace App\Application\Events;

use App\Domain\Events\EventDispatcher;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

final class MercureEventDispatcher implements EventDispatcher
{
    public function __construct(
        private readonly HubInterface $hub,
        private readonly EventTransformer $eventTransformer,
    ) {
    }

    public function dispatch(mixed $event): void
    {
        $events = $this->eventTransformer->transform($event);
        foreach ($events as $event) {
            $type = "$event->format-$event->type";
            $update = null;
            if ($event->userEmail) {
                $update = new Update([$type, $event->userEmail], $event->data, private: true, type: $type);
            } else {
                $update = new Update($type, $event->data, private: false, type: $type);
            }
            $this->hub->publish($update);
        }
    }
}
