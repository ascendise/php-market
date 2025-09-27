<?php

declare(strict_types=1);

namespace App\Application\Market;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int,Offer>
 */
final class OffersDto implements IteratorAggregate
{
    private array $offers = [];

    public function __construct(OfferDto ...$offers)
    {
        $this->offers = $offers ?? [];
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->offers);
    }
}
