<?php

declare(strict_types=1);

namespace App\Application\Market;

interface MarketService
{
    public function listOffers(): OffersDto;
    public function createOffer(OfferDto $offerDto): void;
    public function buyOffer(OfferDto $offerDto): void;
}
