<?php

declare(strict_types=1);

namespace App\Tests\Domain\Market;

use App\Domain\Market\Balance;
use App\Domain\Market\Inventory;
use App\Domain\Market\Item;
use App\Domain\Market\Trader;
use App\Domain\Market\TraderRepository;

final class MemoryTraderRepository extends TraderRepository
{
    /**
     * @var array<string, Trader>
     */
    private array $traders = [];

    public function __construct(Trader ...$traders)
    {
        foreach ($traders as $trader) {
            $this->addCopy($trader);
            $this->traders += [$trader->id() => $trader];
        }
    }

    private function addCopy(Trader $trader): void
    {
        $this->traders += [$trader->id() => $this->copyTrader($trader)];
    }

    private function copyTrader(Trader $trader): Trader
    {
        $inventory = new Inventory();
        foreach ($trader->listInventory() as $item) {
            $new = new Item($item->product(), $item->quantity());
            $inventory->add($new);
        }

        return new Trader($trader->id(), $inventory, new Balance($trader->balance()));
    }

    protected function findFromStore(string $id): ?Trader
    {
        if (!array_key_exists($id, $this->traders)) {
            return null;
        }
        $trader = $this->traders[$id];

        return $this->copyTrader($trader);
    }

    protected function updateFromStore(Trader $trader): void
    {
        $this->traders[$trader->id()] = $trader;
    }
}
