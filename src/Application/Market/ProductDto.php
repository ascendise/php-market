<?php

declare(strict_types=1);

namespace App\Application\Market;

final class ProductDto
{
    public function __construct(public readonly string $name)
    {
    }
}
