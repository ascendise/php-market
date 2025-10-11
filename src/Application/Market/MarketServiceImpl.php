<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\Market;
use App\Domain\Market\TraderRepository;
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
        $seller = $this->traderRegister->find($sellerId->toString());
        $offer = $seller->sell(
            $createOffer->product->toEntity(),
            $createOffer->pricePerItem,
            $createOffer->quantity
        );
        $newOffer = $this->market->createOffer($offer);
        $this->traderRegister->update($seller);
        return OfferDto::fromEntity($newOffer);
    }

    public function buyOffer(Uuid $buyerId, Uuid $offerId): TraderDto
    {
        $buyer = $this->traderRegister->find($buyerId->toString());
        $offer = $this->market->findOffer($offerId->toString());
        $this->market->transact($buyer, $offer);
        $this->traderRegister->update($offer->seller());
        $this->traderRegister->update($buyer);
        return TraderDto::fromEntity($buyer);
    }
}
