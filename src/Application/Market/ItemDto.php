<?php

declare(strict_types=1);

namespace App\Application\Market;

use InvalidArgumentException;

final class ItemDto
{
    public function __construct(
        public readonly ProductDto $product,
        public readonly int $quantity
    ) {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantity cannot be zero or less!');
        }
    }
}
