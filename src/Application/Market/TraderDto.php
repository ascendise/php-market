<?php

declare(strict_types=1);

namespace App\Application\Market;

use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

final class TraderDto
{
    public function __construct(
        public readonly Uuid $id,
        public readonly int $balance,
        public readonly InventoryDto $inventory,
    ) {
        if ($balance < 0) {
            throw new InvalidArgumentException("Trader can't have balance less than zero!");
        }
    }
}
