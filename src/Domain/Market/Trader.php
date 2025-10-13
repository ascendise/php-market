<?php

declare(strict_types=1);

namespace App\Domain\Market;

use IteratorAggregate;

final class Trader implements Seller, Buyer
{
    public function __construct(
        private readonly string $id,
        private readonly Inventory $inventory,
        private readonly Balance $balance,
    ) {
    }

    public function sell(Product $product, int $price, int $quantity): CreateOffer
    {
        $item = $this->inventory->remove($product, $quantity);

        return new CreateOffer($item->product(), $price, $item->quantity(), $this);
    }

    public function buy(Offer $offer): void
    {
        $item = new Item($offer->product(), $offer->quantity());
        $payment = $this->balance->withdraw($offer->totalPrice());
        $offer->seller()->receivePayment($payment);
        $this->inventory->add($item);
    }

    public function receivePayment(Payment $payment): void
    {
        $this->balance->deposit($payment);
    }

    /*
    * @return IteratorAggregate<string, Item>
    */
    public function listInventory(): \IteratorAggregate
    {
        return $this->inventory;
    }

    public function balance(): int
    {
        return $this->balance->amount();
    }

    public function id(): string
    {
        return $this->id;
    }
}
