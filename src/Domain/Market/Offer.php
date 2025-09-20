<?php

declare(strict_types=1);

namespace App\Domain\Market;

use InvalidArgumentException;

final class Offer
{
    private string $productName;
    private int $price;
    private int $quantity;
    private Seller $seller;

    public function __construct(string $productName, int $price, int $quantity, Seller $seller)
    {
        if ($price <= 0) {
            throw new InvalidArgumentException("Price can't be zero or less!");
        }
        if ($quantity <= 0) {
            throw new InvalidArgumentException("Price can't be zero or less!");
        }
        $this->productName = productName;
        $this->price = price;
        $this->quantity = quantity;
        $this->seller = seller;
    }

    public function productName(): string
    {
        return $this->productName;
    }

    public function price(): int
    {
        return $this->price;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function seller(): Seller
    {
        return $this->seller;
    }
}
