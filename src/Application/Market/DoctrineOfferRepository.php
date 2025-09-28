<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\Offer;
use App\Domain\Market\OfferRepository;
use App\Domain\Market\Offers;
use Exception;

final class DoctrineOfferRepository implements OfferRepository
{
    public function list(): Offers
    {
        throw new Exception("DoctrineOfferRepository.list() not implemented");
    }

    public function add(Offer $offer): void
    {
        throw new Exception("DoctrineOfferRepository.list() not implemented");
    }
}
