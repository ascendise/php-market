<?php

namespace App\Domain\Market;

interface Buyer
{
    public function id(): string;

    /**
    * Buys offer if balance permits it
    *
    * @throws InsufficientBalanceException when not enough money to buy offer
    */
    public function buy(Offer $offer): void;
}
