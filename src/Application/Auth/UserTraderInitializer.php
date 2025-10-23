<?php

declare(strict_types=1);

namespace App\Application\Auth;

use App\Entity\Market\Item;
use App\Entity\Market\Trader;

final class UserTraderInitializer implements TraderInitializer
{
    public function __construct(private readonly InitState $initState)
    {
    }

    public function init(): Trader
    {
        $trader = new Trader();
        $trader->setBalance($this->initState->balance());
        foreach ($this->initState->inventory() as $item) {
            $newItem = new Item();
            $newItem->setProductName($item->product()->name());
            $newItem->setQuantity($item->quantity());
            $trader->addInventory($newItem);
        }

        return $trader;
    }
}
