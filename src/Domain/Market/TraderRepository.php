<?php

declare(strict_types=1);

namespace App\Domain\Market;

abstract class TraderRepository
{
    /**
     * Id for stateless bot traders to use a temporary trader for processing transactions.
     */
    public const string STUB_TRADER_ID = 'stubtrader';

    public function find(string $id): ?Trader
    {
        if ($this::STUB_TRADER_ID == $id) {
            return new Trader($this::STUB_TRADER_ID, new Inventory(), new Balance(0));
        }

        return $this->findFromStore($id);
    }

    abstract protected function findFromStore(string $id): ?Trader;

    public function update(Trader $trader): void
    {
        if ($this::STUB_TRADER_ID == $trader->id()) {
            return;
        }
        $this->updateFromStore($trader);
    }

    abstract protected function updateFromStore(Trader $trader): void;
}
