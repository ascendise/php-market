<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\Trader;
use App\Domain\Market\TraderRepository;
use App\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

final class DoctrineTraderRepository implements TraderRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function find(string $id): Trader
    {
        $uuid = Uuid::fromString($id);
        $trader = $this->entityManager->getRepository(Entity\Market\Trader::class)->find($id);
        if (!trader || !$trader instanceof Entity\Market\Trader) {
            return null;
        }
        return new $trader->toEntity();
    }

    public function update(Trader $trader): void
    {
        $newTrader = Entity\Market\Trader::fromEntity($trader);
        $this->entityManager->refresh($newTrader);
        $this->entityManager->persist($newTrader);
        $this->entityManager->flush($newTrader);
    }
}
