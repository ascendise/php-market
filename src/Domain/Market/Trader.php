<?php

declare(strict_types=1);

namespace App\Domain\Market;

use InsufficientStockException;

final class Trader implements Seller
{
    private Inventory $inventory;
    private Balance $balance;

    public function __construct(Inventory $inventory, Balance $balance)
    {
        $this->inventory = $inventory;
        $this->balance = $balance;
    }

    /**
    * @throws InsufficientStockException when trying to create an offer without enough product in your inventory
    */
    public function sell(Product $product, int $price, int $quantity): Offer
    {
        $item = $this->inventory->remove($product, $quantity);
        return new Offer($item->product(), $price, $item->quantity(), $this);
    }
}
