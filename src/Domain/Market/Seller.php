<?php

namespace App\Domain\Market;

/**
* Implemented by actors that create offers on the market
*/
interface Seller
{
    /* Create a new offer to be added to the market */
    public function sell(Product $product, int $price, int $quantity): Offer;
}
