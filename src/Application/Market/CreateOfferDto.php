<?php

declare(strict_types=1);

namespace App\Application\Market;

final class CreateOfferDto
{
    public function __construct(
        public readonly ProductDto $product,
        public readonly int $quantity,
        public readonly int $pricePerItem,
    ) {
    }
}
