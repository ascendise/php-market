<?php

declare(strict_types=1);

namespace App\Domain\Bots;

use App\Domain\Market\Product;

final class ConsumeRate
{
    public function __construct(
        public readonly Product $product,
        public readonly Range|int $budget,
        public readonly Range|int $buyingVolume,
    ) {
    }
}
