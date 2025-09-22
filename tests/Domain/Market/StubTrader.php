<?php

namespace App\Tests\Domain\Market;

use App\Domain\Market\Offer;
use App\Domain\Market\Product;
use App\Domain\Market\Seller;

class StubTrader implements Seller
{
    public function sell(Product $product, int $price, int $quantity): Offer
    {
        return new Offer($product, $price, $quantity, $this);
    }
}
