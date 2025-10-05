<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\Market;
use App\Domain\Market\TraderRepository;
use Exception;
use Symfony\Component\Uid\Uuid;

final class MarketServiceImpl implements MarketService
{
    public function __construct(
        private readonly Market $market,
        private readonly TraderRepository $traderRegister,
    ) {
    }

    public function listOffers(): OffersDto
    {
        $offers = $this->market->listOffers();
        return OffersDto::fromEntity($offers);
    }

    public function createOffer(Uuid $sellerId, CreateOfferDto $createOffer): OfferDto
    {
        $seller = $this->traderRegister->findTrader($sellerId->toString());
        $offer = $seller->sell(
            $createOffer->product->toEntity(),
            $createOffer->pricePerItem,
            $createOffer->quantity
        );
        $this->market->createOffer($offer);
        $this->traderRegister->update($seller);
        return OfferDto::fromEntity($offer);
    }

    public function buyOffer(Uuid $buyerId, OfferDto $offerDto): void
    {
        throw new Exception('MarketServiceImpl.buyOffer() not implemented');
    }
}
