<?php

declare(strict_types=1);

namespace App\Application\Bots;

final class FrequencyDto
{
    public function __construct(
        public readonly int $seconds = 0,
        public readonly int $minutes = 0,
        public readonly int $hours = 0,
    ) {
    }

    public static function fromDateInterval(\DateInterval $interval): self
    {
        return new FrequencyDto(
            seconds: $interval->s,
            minutes: $interval->i,
            hours: $interval->h,
        );
    }

    public function toDateInterval(): \DateInterval
    {
        $s = $this->seconds;
        $m = $this->minutes;
        $h = $this->hours;

        return new \DateInterval("PT{$h}H{$m}M{$s}S");
    }

    public function __toString(): string
    {
        $str = '';
        if ($this->hours > 0) {
            $str .= " {$this->hours} hours";
        }
        if ($this->minutes > 0) {
            $str .= " {$this->minutes} minutes";
        }
        if ($this->seconds > 0) {
            $str .= " {$this->seconds} seconds";
        }
        if ('' === $str) {
            return 'never';
        } else {
            return "every $str";
        }
    }
}
