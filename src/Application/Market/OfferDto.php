<?php

declare(strict_types=1);

namespace App\Application\Market;

use InvalidArgumentException;

final class OfferDto
{
    public function __construct(
        public readonly ProductDto $product,
        public readonly int $quantity,
        public readonly int $totalPrice,
    ) {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantity cannot be less than zero!');
        }
        if ($totalPrice <= 0) {
            throw new InvalidArgumentException('Price cannot be less than zero!');
        }
    }
}
