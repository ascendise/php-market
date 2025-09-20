<?php

declare(strict_types=1);

namespace App\Domain\Market;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<mixed,mixed>
 */
final class Offers implements IteratorAggregate
{
    private array $offers;

    public function __construct(Offer ...$offers)
    {
        $this->offers = $offers;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->offers);
    }
}
