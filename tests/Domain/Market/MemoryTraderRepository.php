<?php

declare(strict_types=1);

namespace App\Tests\Domain\Market;

use App\Domain\Market\Balance;
use App\Domain\Market\Inventory;
use App\Domain\Market\Item;
use App\Domain\Market\Trader;
use App\Domain\Market\TraderRepository;

final class MemoryTraderRepository implements TraderRepository
{
    private array $traders = [];

    public function __construct(Trader ...$traders)
    {
        foreach ($traders as $trader) {
            $this->traders += [$trader->id() => $trader];
        }
    }

    public function findTrader(string $id): Trader
    {
        $trader = $this->traders[$id];
        $inventory = new Inventory();
        foreach ($trader->listInventory() as $item) {
            $new = new Item($item->product(), $item->quantity());
            $inventory->add($new);
        }
        return new Trader($trader->id(), $inventory, new Balance($trader->balance()));
    }

    public function update(Trader $trader): void
    {
        $this->traders[$trader->id()] = $trader;
    }
}
