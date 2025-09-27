<?php

declare(strict_types=1);

namespace App\Domain\Market;

final class Payment
{
    private readonly int $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    public function amount(): int
    {
        return $this->amount;
    }
}
