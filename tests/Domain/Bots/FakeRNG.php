<?php

declare(strict_types=1);

namespace App\Tests\Domain\Bots;

use App\Domain\Bots\RNG;

/*
* Fake RNG that always returns $min or $max depending on configured strategy
*/
final class FakeRNG extends RNG
{
    public function __construct(private readonly FakeRNGStrategy $strategy)
    {
    }

    #[\Override]
    public function rand(int $min, int $max): int
    {
        return match ($this->strategy) {
            FakeRNGStrategy::UseMin => $min,
            FakeRNGStrategy::UseMax => $max,
        };
    }
}
