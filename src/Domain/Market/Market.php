<?php

declare(strict_types=1);

namespace App\Domain\Market;

final class Market
{
    private OfferRepository $offerRepository;

    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function listOffers(): Offers
    {
        return $this->offerRepository->list();
    }

    public function findOffer(string $id): Offer
    {
        return $this->offerRepository->findById($id);
    }

    public function createOffer(CreateOffer $offer): Offer
    {
        return $this->offerRepository->create($offer);
    }

    public function transact(Buyer $buyer, Offer $offer): void
    {
        $buyer->buy($offer);
        $this->offerRepository->remove($offer->id());
    }
}
