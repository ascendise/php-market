<?php

declare(strict_types=1);

namespace App\Domain\Bots;

use App\Domain\Market\Product;

final class ProduceRate
{
    public function __construct(
        public readonly Product $product,
        public readonly Range|int $tradingVolume,
        public readonly Range|int $offerQuantity,
        public readonly Range|int $pricePerItem,
    ) {
    }
}
