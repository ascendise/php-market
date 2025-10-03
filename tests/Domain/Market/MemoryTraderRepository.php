<?php

declare(strict_types=1);

namespace App\Tests\Domain\Market;

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
        return $this->traders[$id];
    }
}
