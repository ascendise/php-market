<?php

declare(strict_types=1);

namespace App\Application\Market;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int,ItemDto>
 */
final class InventoryDto implements IteratorAggregate
{
    private array $items = [];

    public function __construct(ItemDto ...$items)
    {
        $this->items = $items ?? [];
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
