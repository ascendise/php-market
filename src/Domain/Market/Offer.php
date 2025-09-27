<?php

declare(strict_types=1);

namespace App\Domain\Market;

use InvalidArgumentException;

final class Offer
{
    private Product $product;
    private int $quantity;
    private int $totalPrice;
    private Seller $seller;

    public function __construct(Product $product, int $pricePerItem, int $quantity, Seller $seller)
    {
        if ($pricePerItem <= 0) {
            throw new InvalidArgumentException("Price can't be zero or less!");
        }
        if ($quantity <= 0) {
            throw new InvalidArgumentException("Price can't be zero or less!");
        }
        $this->product = $product;
        $this->quantity = $quantity;
        $this->totalPrice = $pricePerItem * $quantity;
        $this->seller = $seller;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function totalPrice(): int
    {
        return $this->totalPrice;
    }

    public function seller(): Seller
    {
        return $this->seller;
    }
}
