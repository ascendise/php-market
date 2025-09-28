<?php

declare(strict_types=1);

namespace App\Domain\Market;

use InvalidArgumentException;

final class Item
{
    private readonly Product $product;
    private int $quantity;

    public function __construct(Product $product, int $quantity)
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException("Quantity can't be zero or less!");
        }
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    /**
    * @return Item containing the new quantity qfter adding item
    * @throws InvalidArgumentException when trying to add two different products
    */
    public function add(Item $item): Item
    {
        if ($item->product() != $this->product()) {
            throw new InvalidArgumentException("Cannot add two items with different products!");
        }
        $newQuantity = $this->quantity() + $item->quantity();
        return new Item($this->product(), $newQuantity);
    }

    /**
    * @return Item containing the new quantity after removing item or null if no items left
    * @throws InvalidArgumentException when trying to add two different products
    * @throws InsufficientStockException when trying to remove more items than available
    */
    public function remove(Item $item): ?Item
    {
        if ($item->product() != $this->product()) {
            throw new InvalidArgumentException("Cannot add two items with different products!");
        }
        $stocked = $this->quantity();
        if ($stocked == 0 || $stocked < $item->quantity()) {
            throw new InsufficientStockException($item->quantity(), $stocked, $this->product());
        }
        $newQuantity = $this->quantity() - $item->quantity();
        if ($newQuantity == 0) {
            return null;
        }
        return new Item($this->product, $newQuantity);
    }
}
