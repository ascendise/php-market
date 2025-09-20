<?php

declare(strict_types=1);

namespace App\Domain\Market;

final class Market
{
    private OfferRepository $offerRepository;

    public function __construct(Offers $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function listOffers(): Offers
    {
        return $this->offers;
    }
}
