<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\Offer;
use App\Domain\Market\Seller;

final class CreateOfferDto
{
    public function __construct(
        public readonly ProductDto $product,
        public readonly int $quantity,
        public readonly int $pricePerItem,
    ) {
    }

    public function toEntity(Seller $seller): Offer
    {
        return new Offer(
            $this->product->toEntity(),
            pricePerItem: $this->pricePerItem,
            quantity: $this->quantity,
            seller: $seller
        );
    }
}
