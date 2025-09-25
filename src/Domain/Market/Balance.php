<?php

declare(strict_types=1);

namespace App\Domain\Market;

use InvalidArgumentException;

final class Balance
{
    private readonly int $initialBalance;

    public function __construct(int $initialBalance)
    {
        if ($initialBalance < 0) {
            throw new InvalidArgumentException("Balance can't be less than zero!");
        }
        $this->initialBalance = $initialBalance;
    }
}
