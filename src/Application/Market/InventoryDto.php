<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\Item;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int,ItemDto>
 */
final class InventoryDto implements IteratorAggregate
{
    /* @var array<string,ItemDto> $items */
    private array $items = [];

    public function __construct(ItemDto ...$items)
    {
        $this->items = $items ?? [];
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @param array<mixed,Item> $items
     */
    public static function fromEntities(array $items): InventoryDto
    {
        $inventory = new InventoryDto();
        foreach ($items as $item) {
            $inventory->items += [$item->product()->name() => ItemDto::fromEntity($item)];
        }
        return $inventory;
    }
}
