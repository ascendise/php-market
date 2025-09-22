<?php

declare(strict_types=1);

namespace App\Domain\Market;

use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<mixed,Item>
 */
class Inventory implements IteratorAggregate
{
    private array $items;

    public function __construct(Item ...$items)
    {
        $this->items = $items ?? [];
    }

    public function getIterator(): Traversable
    {
        return $this->items;
    }
}
