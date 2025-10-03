<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\Product;

final class ProductDto
{
    public function __construct(public readonly string $name)
    {
    }

    public static function fromEntity(Product $product): ProductDto
    {
        return new ProductDto($product->name());
    }

    public function toEntity(): Product
    {
        return new Product($this->name);
    }
}
