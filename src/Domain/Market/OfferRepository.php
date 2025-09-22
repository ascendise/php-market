<?php

declare(strict_types=1);

namespace App\Domain\Market;

interface OfferRepository
{
    public function listOffers(): Offers;
    public function addOffer(Offer $offer): Offers;
}
