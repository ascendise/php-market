<?php

declare(strict_types=1);

namespace App\Domain\Market;

use Exception;
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

    public function add(Item $item): void
    {
        throw new Exception("Not implemented");
    }

    public function remove(Product $product, int $quantity): ?Item
    {
        foreach ($this->items as $item) {
            if ($item->product() == $product) {
                $item->setQuantity($item->quantity() - $quantity);
                return new Item($item->product(), $quantity);
            }
        }
        return null;
    }

    public function quantityOf(Product $product): int
    {
        foreach ($this->items as $item) {
            if ($item->product() == $product) {
                return $item->quantity();
            }
        }
        return null;
    }
}
