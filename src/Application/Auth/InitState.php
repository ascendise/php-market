<?php

namespace App\Application\Auth;

use App\Domain\Market\Inventory;

final class InitState
{
    public function __construct(
        private readonly int $balance = 300000,
        private readonly Inventory $inventory = new Inventory(),
    ) {
    }

    public function balance(): int
    {
        return $this->balance;
    }

    public function inventory(): Inventory
    {
        return $this->inventory;
    }
}
