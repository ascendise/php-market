<?php

declare(strict_types=1);

namespace App\Domain\Market;

use Exception;

final class Trader implements Seller
{
    private Inventory $inventory;
    private int $balance;

    public function sell(Product $product, int $price, int $quantity): Offer
    {
        throw new Exception('Not implemented');
    }
}
