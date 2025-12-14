<?php

declare(strict_types=1);

namespace App\Domain\Bots;

final class Range
{
    public function __construct(
        public readonly int $min,
        public readonly int $max,
    ) {
        if ($min >= $max) {
            throw new \InvalidArgumentException("\$min($min) has to be less than \$max($max)");
        }
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

    public function __toString(): string
    {
        if ($this->min == $this->max) {
            return (string) $this->min;
        } else {
            return "$this->min-$this->max";
        }
    }
}
