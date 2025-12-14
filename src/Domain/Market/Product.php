<?php

namespace App\Domain\Market;

class Product
{
    public function __construct(public readonly string $name)
    {
    }
}
