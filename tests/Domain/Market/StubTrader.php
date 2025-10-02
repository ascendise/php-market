<?php

namespace App\Tests\Domain\Market;

use App\Domain\Market\Buyer;
use App\Domain\Market\Offer;
use App\Domain\Market\Payment;
use App\Domain\Market\Product;
use App\Domain\Market\Seller;

class StubTrader implements Seller, Buyer
{
    public function id(): string
    {
        return 'id';
    }

    public function sell(Product $product, int $price, int $quantity): Offer
    {
        return new Offer($product, $price, $quantity, $this);
    }

    public function buy(Offer $offer): void
    {
    }

    public function receivePayment(Payment $payment): void
    {
    }
}
