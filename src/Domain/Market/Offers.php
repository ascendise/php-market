<?php

declare(strict_types=1);

namespace App\Domain\Market;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<string,Offer>
 */
final class Offers implements IteratorAggregate
{
    /* @param array<string, Offer> */
    private array $offers = [];

    public function __construct(Offer ...$offers)
    {
        foreach ($offers as $offer) {
            $this->offers += [$offer->id() => $offer];
        }
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->offers);
    }
}
