<?php

declare(strict_types=1);

namespace App\Domain\Market;

final class OfferCreatedEvent
{
    public function __construct(
        private readonly Offer $newOffer,
    ) {
    }

    public function newOffer(): Offer
    {
        return $this->newOffer;
    }
}
