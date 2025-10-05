<?php

declare(strict_types=1);

namespace App\Domain\Market;

interface OfferRepository
{
    public function list(): Offers;
    public function findById(string $id): ?Offer;
    public function create(CreateOffer $createOffer): Offer;
}
