<?php

declare(strict_types=1);

namespace App\Domain\Bots;

/*
* Wrapper around built-in rand()
*/
class RNG
{
    public function rand(int $min, int $max): int
    {
        return rand($min, $max);
    }
}
