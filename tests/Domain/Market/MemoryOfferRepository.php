<?php

namespace App\Tests\Domain\Market;

use App\Domain\Market\Offer;
use App\Domain\Market\OfferRepository;
use App\Domain\Market\Offers;

class MemoryOfferRepository implements OfferRepository
{
    private Offers $offers;

    public function __construct(Offers $initOffers)
    {
        $this->offers = $initOffers;
    }

    public function listOffers(): Offers
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): Offers
    {
        $this->offers[] = $offer;
    }
}
