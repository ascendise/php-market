<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\OfferCreatedEvent;

final class OfferCreatedEventDto
{
    public function __construct(
        public readonly OfferDto $newOffer,
    ) {
    }

    public function newOffer(): OfferDto
    {
        return $this->newOffer;
    }

    public static function fromEntity(OfferCreatedEvent $event): self
    {
        return new OfferCreatedEventDto(OfferDto::fromEntity($event->newOffer()));
    }
}
