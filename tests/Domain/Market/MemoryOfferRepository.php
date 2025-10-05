<?php

namespace App\Tests\Domain\Market;

use App\Domain\Market\CreateOffer;
use App\Domain\Market\Offer;
use App\Domain\Market\OfferRepository;
use App\Domain\Market\Offers;
use Generator;
use Iterator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

class MemoryOfferRepository implements OfferRepository
{
    /* @var array<string, Offer> $offers */
    private array $offers;

    /* @var Iterator<mixed, Uuid> $uuidGenerator */
    private Iterator $uuidGenerator;

    /* @param Iterator<mixed,mixed> $uuidGenerator */
    public function __construct(Offers $initOffers, ?Iterator $uuidGenerator = null)
    {
        $this->offers = iterator_to_array($initOffers);
        $this->uuidGenerator = $uuidGenerator ?? $this->randomGenerator();
    }

    /* @return Generator<UuidV7> */
    private function randomGenerator(): Iterator
    {
        while (true) {
            yield Uuid::v7();
        }
    }

    public function list(): Offers
    {
        return new Offers(...$this->offers);
    }

    public function create(CreateOffer $createOffer): Offer
    {
        $id = $this->getId();
        $offer = $createOffer->toOffer($id);
        $this->offers += [$offer->id() => $offer];
        return $offer;
    }

    private function getId(): string
    {
        $uuid = $this->uuidGenerator->current();
        $this->uuidGenerator->next();
        if (!$uuid) {
            $this->uuidGenerator->rewind();
            $uuid = $this->uuidGenerator->current();
        }
        return $uuid->toString();
    }

    public function findById(string $id): ?Offer
    {
        return $this->offers[$id];
    }
}
