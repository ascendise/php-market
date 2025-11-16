<?php

declare(strict_types=1);

namespace App\Tests\Domain\Bots;

enum FakeRNGStrategy
{
    case UseMin;
    case UseMax;
}
