<?php

declare(strict_types=1);

namespace App\Domain\Market;

final class Market
{
    public function __construct(
        private readonly OfferRepository $offerRepository,
        private readonly TraderRepository $traderRepository,
    ) {
    }

    public function listOffers(): Offers
    {
        return $this->offerRepository->list();
    }

    public function findOffer(string $id): Offer
    {
        return $this->offerRepository->findById($id);
    }

    public function findTrader(string $id): ?Trader
    {
        return $this->traderRepository->find($id);
    }

    public function createOffer(CreateOffer $createOffer): Offer
    {
        $offer = $this->offerRepository->create($createOffer);
        $this->persistTrader($createOffer->seller());

        return $offer;
    }

    private function persistTrader(mixed $trader): void
    {
        if ($trader instanceof Trader) {
            $this->traderRepository->update($trader);
        }
    }

    public function transact(Buyer $buyer, Offer $offer): void
    {
        $buyer->buy($offer);
        $this->offerRepository->remove($offer->id());
        $this->persistTrader($buyer);
        $this->persistTrader($offer->seller());
    }
}
