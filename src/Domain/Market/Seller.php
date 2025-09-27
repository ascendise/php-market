<?php

namespace App\Domain\Market;

/**
* Implemented by actors that create offers on the market
*/
interface Seller
{
    /**
    * Create a new offer to be added to the market
    * @throws InsufficientStockException when trying to create an offer without enough product in your inventory
    */
    public function sell(Product $product, int $price, int $quantity): Offer;
}
