<?php

namespace App\Tests\Domain\Market;

use App\Domain\Market\CreateOffer;
use App\Domain\Market\Offer;
use App\Domain\Market\OfferRepository;
use App\Domain\Market\Offers;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

class MemoryOfferRepository implements OfferRepository
{
    /**
     * @var array<string, Offer>
     */
    private array $offers = [];

    /**
     * @var \Iterator<UuidV7>
     */
    private \Iterator $uuidGenerator;

    /**
     * @param \Iterator<UuidV7> $uuidGenerator
     */
    public function __construct(Offers $initOffers, ?\Iterator $uuidGenerator = null)
    {
        $this->offers = iterator_to_array($initOffers);
        $this->uuidGenerator = $uuidGenerator ?? $this->randomGenerator();
    }

    /* @return \Iterator<UuidV7> */
    private function randomGenerator(): \Iterator
    {
        // @phpstan-ignore while.alwaysTrue
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
        if (null == $uuid) {
            $this->uuidGenerator->rewind();
            $uuid = $this->uuidGenerator->current();
        }

        return $uuid->toString();
    }

    public function findById(string $id): ?Offer
    {
        $offer = $this->offers[$id];

        return new Offer(
            $offer->id(),
            $offer->product(),
            $offer->pricePerItem(),
            $offer->quantity(),
            $offer->seller()
        );
    }

    public function remove(string $id): void
    {
        unset($this->offers[$id]);
    }
}
