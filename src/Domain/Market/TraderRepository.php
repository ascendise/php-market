<?php

declare(strict_types=1);

namespace App\Domain\Market;

interface TraderRepository
{
    public function findTrader(string $id): Trader;
    public function update(Trader $trader): void;
}
