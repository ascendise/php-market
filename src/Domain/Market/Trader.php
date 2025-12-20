<?php

declare(strict_types=1);

namespace App\Domain\Market;

use App\Domain\Events\EventDispatcher;
use App\Domain\Events\NoopEventDispatcher;
use IteratorAggregate;

final class Trader implements Seller, Buyer
{
    private readonly EventDispatcher $eventDispatcher;

    public function __construct(
        private readonly string $id,
        private readonly Inventory $inventory,
        private readonly Balance $balance,
        ?EventDispatcher $eventDispatcher = null,
    ) {
        $this->eventDispatcher = $eventDispatcher ?? new NoopEventDispatcher();
    }

    public function sell(Product $product, int $price, int $quantity): OfferCommand
    {
        $item = $this->inventory->remove($product, $quantity);

        return new OfferCommand($item->product(), $price, $item->quantity(), $this);
    }

    public function buy(Offer $offer): void
    {
        $item = new Item($offer->product(), $offer->quantity());
        $payment = $this->balance->withdraw($offer->totalPrice());
        $offer->seller()->receivePayment($payment);
        $this->inventory->add($item);
        $this->eventDispatcher->dispatch(new BalanceChangedEvent($this, $this->balance));
    }

    public function receivePayment(Payment $payment): void
    {
        $this->balance->deposit($payment);
        $this->eventDispatcher->dispatch(new BalanceChangedEvent($this, $this->balance));
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
