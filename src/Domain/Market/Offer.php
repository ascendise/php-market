<?php

declare(strict_types=1);

namespace App\Domain\Market;

final class Offer
{
    public function __construct(
        private readonly string $id,
        private readonly Product $product,
        private readonly int $pricePerItem,
        private readonly int $quantity,
        private readonly Seller $seller,
    ) {
        if ($pricePerItem <= 0) {
            throw new \InvalidArgumentException("Price can't be zero or less!");
        }
        if ($quantity <= 0) {
            throw new \InvalidArgumentException("Price can't be zero or less!");
        }
    }

    public function id(): string
    {
        return $this->id;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function pricePerItem(): int
    {
        return $this->pricePerItem;
    }

    public function totalPrice(): int
    {
        return $this->quantity * $this->pricePerItem;
    }

    public function seller(): Seller
    {
        return $this->seller;
    }
}
