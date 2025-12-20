<?php

declare(strict_types=1);

namespace App\Domain\Market;

use App\Domain\Events\EventDispatcher;
use App\Domain\Events\NoopEventDispatcher;

final class Market
{
    private readonly EventDispatcher $eventDispatcher;

    public function __construct(
        private readonly OfferRepository $offerRepository,
        private readonly TraderRepository $traderRepository,
        ?EventDispatcher $eventDispatcher = null,
    ) {
        $this->eventDispatcher = $eventDispatcher ?? new NoopEventDispatcher();
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

    public function createOffer(OfferCommand $createOffer): Offer
    {
        $offer = $this->offerRepository->create($createOffer);
        $this->persistTrader($createOffer->seller());
        $this->eventDispatcher->dispatch(new OfferCreatedEvent($offer));

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
        $this->eventDispatcher->dispatch(new OfferSoldEvent($offer));
    }
}
