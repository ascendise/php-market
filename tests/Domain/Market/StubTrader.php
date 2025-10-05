<?php

namespace App\Tests\Domain\Market;

use App\Domain\Market\Buyer;
use App\Domain\Market\CreateOffer;
use App\Domain\Market\Offer;
use App\Domain\Market\Payment;
use App\Domain\Market\Product;
use App\Domain\Market\Seller;

class StubTrader implements Seller, Buyer
{
    public function id(): string
    {
        return '0199ab17-17d2-79b4-9483-c95a6365ee96';
    }

    public function sell(Product $product, int $price, int $quantity): CreateOffer
    {
        return new CreateOffer($product, $price, $quantity, $this);
    }

    public function buy(Offer $offer): void
    {
    }

    public function receivePayment(Payment $payment): void
    {
    }
}
