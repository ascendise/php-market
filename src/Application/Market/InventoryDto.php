<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\Inventory;
use App\Domain\Market\Item;

/**
 * @implements \IteratorAggregate<int,ItemDto>
 */
final class InventoryDto implements \IteratorAggregate
{
    /**
     * @var array<string,ItemDto>
     */
    private array $items = [];

    public function __construct(ItemDto ...$items)
    {
        $this->items = $items;
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->items);
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

    public function toEntity(): Inventory
    {
        $items = array_map(fn (ItemDto $i) => $i->toEntity(), $this->items);

        return new Inventory(...$items);
    }
}
