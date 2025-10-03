<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\OfferRepository;
use Exception;

final class MarketServiceImpl implements MarketService
{
    public function __construct(
        private readonly OfferRepository $offerRepository
    ) {
    }

    public function listOffers(): OffersDto
    {
        $offers = $this->offerRepository->list();
        return OffersDto::fromEntity($offers);
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
