<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Application\HAL\HALResource;
use App\Domain\Market\Offer;
use App\Domain\Market\Offers;
use App\Domain\Market\Trader;

final class CreatedOfferDto extends HALResource
{
    public function __construct(
        public readonly OfferDto $createdOffer,
        public readonly OffersDto $offers,
        public readonly TraderDto $seller,
    ) {
    }

    public static function fromEntity(Offer $createdOffer, Offers $offers, Trader $seller): CreatedOfferDto
    {
        return new CreatedOfferDto(
            OfferDto::fromEntity($createdOffer),
            OffersDto::fromEntity($offers),
            TraderDto::fromEntity($seller)
        );
    }
}
