<?php

declare(strict_types=1);

namespace App\Domain\Market;

use ArrayIterator;
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
            $this->add($item);
        }
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function add(Item $item): void
    {
        $productName = $item->product()->name();
        if ($this->itemExists($productName)) {
            $this->items[$productName]->add($item->quantity());
        } else {
            $this->items += [$productName => $item];
        }
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
        if (!$this->itemExists($product->name())) {
            return 0;
        }
        $item = $this->items[$product->name()];
        return $item->quantity();
    }

    private function itemExists(string $productName): bool
    {
        return array_key_exists($productName, $this->items);
    }
}
