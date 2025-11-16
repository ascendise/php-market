<?php

declare(strict_types=1);

namespace App\Domain\Bots;

final class Range
{
    public function __construct(
        private readonly int $min,
        private readonly int $max,
    ) {
        if ($min >= $max) {
            throw new \InvalidArgumentException("\$min($min) has to be less than \$max($max)");
        }
    }

    public function min(): int
    {
        return $this->min;
    }

    public function max(): int
    {
        return $this->max;
    }

    /*
    * Returns a value in the defined Range
    */
    public function value(?RNG $rng = null): int
    {
        $rng = $rng ?? new RNG();

        return $rng->rand($this->min, $this->max);
    }

    public static function getValue(Range|int $range, ?RNG $rng = null): int
    {
        $rng = $rng ?? new RNG();
        if ($range instanceof Range) {
            return $range->value($rng);
        } else {
            return $range;
        }
    }
}
