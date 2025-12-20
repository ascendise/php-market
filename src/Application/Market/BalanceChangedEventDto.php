<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\BalanceChangedEvent;

final class BalanceChangedEventDto
{
    public function __construct(
        public readonly TraderDto $trader,
        public readonly int $newBalance,
    ) {
    }

    public static function fromEntity(BalanceChangedEvent $event): self
    {
        return new BalanceChangedEventDto(
            TraderDto::fromEntity($event->trader()),
            $event->newBalance()->amount()
        );
    }
}
