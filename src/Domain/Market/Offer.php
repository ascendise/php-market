<?php

declare(strict_types=1);

namespace App\Domain\Market;

final class Offer
{
    private string $productName;
    private int $price;
    private float $quantity;
    private Seller $seller;

    public function __construct(string $productName, int $price, float $quantity, Seller $seller)
    {
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

    public function quantity(): float
    {
        return $this->quantity;
    }

    public function seller(): Seller
    {
        return $this->seller;
    }
}
