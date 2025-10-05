<?php

declare(strict_types=1);

namespace App\Domain\Market;

use InvalidArgumentException;

final class CreateOffer
{
    public function __construct(
        private readonly Product $product,
        private readonly int $pricePerItem,
        private readonly int $quantity,
        private readonly Seller $seller
    ) {
        if ($pricePerItem <= 0) {
            throw new InvalidArgumentException("Price can't be zero or less!");
        }
        if ($quantity <= 0) {
            throw new InvalidArgumentException("Price can't be zero or less!");
        }
    }

    public function toOffer(string $id): Offer
    {
        return new Offer(
            $id,
            $this->product,
            $this->pricePerItem,
            $this->quantity,
            $this->seller
        );
    }
}
