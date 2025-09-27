<?php

declare(strict_types=1);

namespace App\Application\Market;

use InvalidArgumentException;

final class TraderDto
{
    public function __construct(
        public readonly int $balance,
        public readonly InventoryDto $inventory,
    ) {
        if ($balance < 0) {
            throw new InvalidArgumentException("Trader can't have balance less than zero!");
        }
    }
}
