<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\CreateOffer;
use App\Domain\Market\Seller;

final class CreateOfferDto
{
    public function __construct(
        public readonly ProductDto $product,
        public readonly int $quantity,
        public readonly int $pricePerItem,
    ) {
    }

    public function toEntity(Seller $seller): CreateOffer
    {
        return new CreateOffer($this->product->toEntity(), $this->pricePerItem, $this->quantity, $seller);
    }
}
