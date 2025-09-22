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
        return $this->offerRepository->listOffers();
    }

    public function createOffer(Offer $offer): void
    {
    }
}
