<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\OfferCommand;
use App\Domain\Market\Seller;

final class OfferCommandDto
{
    public function __construct(
        public readonly ProductDto $product,
        public readonly int $quantity,
        public readonly int $pricePerItem,
    ) {
    }

    public function toEntity(Seller $seller): OfferCommand
    {
        return new OfferCommand($this->product->toEntity(), $this->pricePerItem, $this->quantity, $seller);
    }
}
