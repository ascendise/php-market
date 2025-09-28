<?php

declare(strict_types=1);

namespace App\Application\Market;

interface MarketService
{
    public function listOffers(): OffersDto;
    public function createOffer(TraderDto $seller, OfferDto $offerDto): void;
    public function buyOffer(TraderDto $buyer, OfferDto $offerDto): void;
}
