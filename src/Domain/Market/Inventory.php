<?php

declare(strict_types=1);

namespace App\Domain\Market;

/**
 * @implements \IteratorAggregate<string,Item>
 */
final class Inventory implements \IteratorAggregate
{
    /**
     * @var array<int, Item>
     */
    private array $items;

    public function __construct(Item ...$items)
    {
        $this->items = [];
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->items);
    }

    public function add(Item $item): void
    {
        $productName = $item->product()->name();
        if ($this->itemExists($productName)) {
            $this->items[$productName] = $this->items[$productName]->add($item);
        } else {
            $this->items += [$productName => $item];
        }
    }

    public function remove(Product $product, int $quantity): Item
    {
        $productName = $product->name();
        if (!$this->itemExists($productName)) {
            throw new InsufficientStockException($quantity, 0, $product);
        }
        $removed = new Item($product, $quantity);
        $leftItems = $this->items[$productName]->remove($removed);
        if (null == $leftItems) {
            unset($this->items[$productName]);
        } else {
            $this->items[$productName] = $leftItems;
        }

        return $removed;
    }

    private function itemExists(string $productName): bool
    {
        return array_key_exists($productName, $this->items);
    }

    public function quantityOf(Product $product): int
    {
        if (!$this->itemExists($product->name())) {
            return 0;
        }
        $item = $this->items[$product->name()];

        return $item->quantity();
    }
}
