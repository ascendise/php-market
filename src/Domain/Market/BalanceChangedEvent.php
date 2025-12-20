<?php

declare(strict_types=1);

namespace App\Domain\Market;

final class BalanceChangedEvent
{
    public function __construct(
        private readonly Trader $trader,
        private readonly Balance $newBalance,
    ) {
    }

    public function trader(): Trader
    {
        return $this->trader;
    }

    public function newBalance(): Balance
    {
        return $this->newBalance;
    }
}
