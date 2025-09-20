<?php

namespace App\Domain\Market;

interface OfferRepository
{
    public function listOffers(): Offers;
    public function addOffer(Offer $offer): Offers;
}
