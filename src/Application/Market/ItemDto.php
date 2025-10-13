<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\Item;

final class ItemDto
{
    public function __construct(
        public readonly ProductDto $product,
        public readonly int $quantity,
    ) {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity cannot be zero or less!');
        }
    }

    public static function fromEntity(Item $item): ItemDto
    {
        return new ItemDto(
            ProductDto::fromEntity($item->product()),
            $item->quantity()
        );
    }

    public function toEntity(): Item
    {
        return new Item($this->product->toEntity(), $this->quantity);
    }
}
