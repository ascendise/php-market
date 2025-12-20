<?php

declare(strict_types=1);

namespace App\Domain\Market;

final class OfferSoldEvent
{
    public function __construct(
        private readonly Offer $soldOffer,
    ) {
    }

    public function soldOffer(): Offer
    {
        return $this->soldOffer;
    }
}
