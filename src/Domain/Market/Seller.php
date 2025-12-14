<?php

namespace App\Domain\Market;

/**
 * Implemented by actors that create offers on the market.
 */
interface Seller
{
    public function id(): string;

    /**
     * Create a new offer to be added to the market.
     *
     * @throws InsufficientStockException when trying to create an offer without enough product in your inventory
     */
    public function sell(Product $product, int $pricePerItem, int $quantity): OfferCommand;

    /**
     * Transfers payment (presumably from a completed sale) from the source to the Seller.
     */
    public function receivePayment(Payment $payment): void;
}
