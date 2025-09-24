<?php

declare(strict_types=1);

namespace App\Domain\Market;

final class Trader implements Seller
{
    private Inventory $inventory;
    private int $balance;

    public function __construct(Inventory $inventory, int $balance)
    {
        $this->inventory = $inventory;
        $this->balance = $balance;
    }

    public function sell(Product $product, int $price, int $quantity): Offer
    {
        $item = $this->inventory->remove($product, $quantity);
        return new Offer($item->product(), $price, $item->quantity(), $this);
    }
}
