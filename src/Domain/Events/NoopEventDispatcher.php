<?php

declare(strict_types=1);

namespace App\Domain\Events;

final class NoopEventDispatcher implements EventDispatcher
{
    public function dispatch(mixed $event): void
    {
        // noop
    }
}
