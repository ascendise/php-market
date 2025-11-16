<?php

declare(strict_types=1);

namespace App\Domain\Bots;

use App\Domain\Market\Product;

final class ProduceRate
{
    public function __construct(
        private readonly Product $product,
        private readonly Range|int $tradingVolume,
        private readonly Range|int $offerQuantity,
        private readonly Range|int $pricePerItem,
    ) {
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function tradingVolume(): Range|int
    {
        return $this->tradingVolume;
    }

    public function offerQuantity(): Range|int
    {
        return $this->offerQuantity;
    }

    public function pricePerItem(): Range|int
    {
        return $this->pricePerItem;
    }
}
