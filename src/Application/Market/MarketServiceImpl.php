<?php

declare(strict_types=1);

namespace App\Application\Market;

use Exception;

final class MarketServiceImpl implements MarketService
{
    public function listOffers(): OffersDto
    {
        throw new Exception('MarketServiceImpl.listOffers() not implemented');
    }

    public function createOffer(TraderDto $seller, OfferDto $offerDto): void
    {
        throw new Exception('MarketServiceImpl.createOffer() not implemented');
    }

    public function buyOffer(TraderDto $buyer, OfferDto $offerDto): void
    {
        throw new Exception('MarketServiceImpl.buyOffer() not implemented');
    }
}
