<?php

declare(strict_types=1);

namespace App\Domain\Market;

use IteratorAggregate;

final class Trader implements Seller, Buyer
{
    private Inventory $inventory;
    private Balance $balance;

    public function __construct(Inventory $inventory, Balance $balance)
    {
        $this->inventory = $inventory;
        $this->balance = $balance;
    }

    public function sell(Product $product, int $price, int $quantity): Offer
    {
        $item = $this->inventory->remove($product, $quantity);
        return new Offer($item->product(), $price, $item->quantity(), $this);
    }

    public function buy(Offer $offer): void
    {
        $item = new Item($offer->product(), $offer->quantity());
        $_ = $this->balance->withdraw($offer->totalPrice());
        $this->inventory->add($item);
    }

    public function listInventory(): IteratorAggregate
    {
        return $this->inventory;
    }

    public function balance(): int
    {
        return $this->balance->amount();
    }
}
