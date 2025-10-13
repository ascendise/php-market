<?php

declare(strict_types=1);

namespace App\Domain\Market;

/**
 * @implements \IteratorAggregate<string,Offer>
 */
final class Offers implements \IteratorAggregate
{
    /**
     * @var array<string, Offer>
     */
    private array $offers = [];

    public function __construct(Offer ...$offers)
    {
        foreach ($offers as $offer) {
            $this->offers += [$offer->id() => $offer];
        }
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->offers);
    }
}
