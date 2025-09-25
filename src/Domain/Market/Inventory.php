<?php

declare(strict_types=1);

namespace App\Domain\Market;

use Exception;
use App\Domain\Market\InsufficientStockException;
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
        $this->items = [];
        foreach ($items as $item) {
            $this->items += [$item->product()->name() => $item];
        }
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
        $stocked = $this->quantityOf($product);
        if ($stocked == 0 || $stocked < $quantity) {
            throw new InsufficientStockException($quantity, $stocked, $product);
        }
        $item = $this->items[$product->name()];
        $removed = $item->remove($quantity);
        return $removed;
    }

    public function quantityOf(Product $product): int
    {
        if (!array_key_exists($product->name(), $this->items)) {
            return 0;
        }
        $item = $this->items[$product->name()];
        return $item->quantity();
    }
}
