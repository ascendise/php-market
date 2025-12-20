<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\Market;
use App\Domain\Market\Offer;
use App\Domain\Market\Trader;
use Symfony\Component\Uid\Uuid;

final class MarketServiceImpl implements MarketService
{
    public function __construct(
        private readonly Market $market,
    ) {
    }

    public function listOffers(): OffersDto
    {
        $offers = $this->market->listOffers();

        return OffersDto::fromEntity($offers);
    }

    public function createOffer(Uuid $sellerId, OfferCommandDto $createOffer): CreatedOfferDto
    {
        $seller = $this->market->findTrader($sellerId->toString());
        $offer = $seller->sell(
            $createOffer->product->toEntity(),
            $createOffer->pricePerItem,
            $createOffer->quantity
        );
        $newOffer = $this->market->createOffer($offer);
        $offers = $this->market->listOffers();

        return CreatedOfferDto::fromEntity($newOffer, $offers, $seller);
    }

    public function buyOffer(Uuid $buyerId, Uuid $offerId): TraderDto
    {
        $buyerId = $buyerId->toString();
        $buyer = $this->market->findTrader($buyerId);
        $offer = $this->market->findOffer($offerId->toString());
        if ($offer->seller()->id() == $buyerId) {
            $offer = MarketServiceImpl::mergeEntities($buyer, $offer);
        }
        $this->market->transact($buyer, $offer);

        return TraderDto::fromEntity($buyer);
    }

    // If Offer->seller and Trader are actually the same entity, this makes sure we are
    // acting on the same reference for easier processing
    private static function mergeEntities(Trader $trader, Offer $offer): Offer
    {
        return new Offer(
            $offer->id(),
            $offer->product(),
            $offer->pricePerItem(),
            $offer->quantity(),
            $trader
        );
    }
}
