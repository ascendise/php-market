<?php

declare(strict_types=1);

namespace App\Domain\Bots;

use App\Domain\Market\Product;

final class ConsumeRate
{
    public function __construct(
        private readonly Product $product,
        private readonly Range|int $budget,
        private readonly Range|int $buyingVolume,
    ) {
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function budget(): Range|int
    {
        return $this->budget;
    }

    public function buyingVolume(): Range|int
    {
        return $this->buyingVolume;
    }
}
