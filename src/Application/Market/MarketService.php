<?php

declare(strict_types=1);

namespace App\Application\Market;

use Symfony\Component\Uid\Uuid;

interface MarketService
{
    public function listOffers(): OffersDto;
    public function createOffer(Uuid $sellerId, CreateOfferDto $createOffer): OfferDto;
    public function buyOffer(Uuid $buyerId, OfferDto $offerDto): void;
}
