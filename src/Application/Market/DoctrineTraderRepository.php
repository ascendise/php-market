<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\Trader;
use App\Domain\Market\TraderRepository;
use App\Entity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineTraderRepository implements TraderRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function find(string $id): ?Trader
    {
        $trader = $this->entityManager->getRepository(Entity\Market\Trader::class)->find($id);
        if (!$trader) {
            return null;
        }
        return $trader->toEntity();
    }

    public function update(Trader $trader): void
    {
        $oldTrader = $this->entityManager->getRepository(Entity\Market\Trader::class)->find($trader->id());
        $newTrader = Entity\Market\Trader::fromEntity($trader);
        $oldTrader->setBalance($newTrader->getBalance());
        $removedItems = [];
        foreach ($oldTrader->getInventory() as $item) {
            $updatedItem = $newTrader->getInventory()
                ->filter(fn ($i) => $i->getProductName() == $item->getProductName());
            if ($updatedItem->isEmpty()) {
                $removedItems[] = $item;
                continue;
            }
            $item->setQuantity($updatedItem->first()->getQuantity());
        }
        foreach ($removedItems as $remove) {
            $oldTrader->removeInventory($remove);
        }
        foreach ($newTrader->getInventory() as $newItem) {
            $oldItem = $oldTrader->getInventory()
                ->filter(fn ($i) => $i->getProductName() == $newItem->getProductName());
            if ($oldItem->isEmpty()) {
                $oldTrader->addInventory($newItem);
            }
        }
        $this->entityManager->flush();
    }
}
