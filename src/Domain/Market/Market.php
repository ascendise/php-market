<?php

declare(strict_types=1);

namespace App\Domain\Market;

final class Market
{
    private Offers $offers;

    public function __construct(Offers $offers)
    {
        $this->offers = $offers;
    }

    public function listOffers(): Offers
    {
        return $this->offers;
    }
}
