<?php

declare(strict_types=1);

namespace App\Domain\Market;

interface OfferRepository
{
    public function list(): Offers;
    public function add(Offer $offer): void;
}
