<?php

declare(strict_types=1);

namespace App\Tests\Application\RateLimit;

use Symfony\Component\RateLimiter\LimiterInterface;
use Symfony\Component\RateLimiter\RateLimit;
use Symfony\Component\RateLimiter\Reservation;

final class StubLimiter implements LimiterInterface
{
    public function __construct(private readonly bool $isOverLimit)
    {
    }

    public function reserve(int $tokens = 1, ?float $maxTime = null): Reservation
    {
        return new Reservation(0, $this->getFakedRateLimit());
    }

    private function getFakedRateLimit(): RateLimit
    {
        $availableTokens = $this->isOverLimit ? 0 : 1000;

        return new RateLimit(
            $availableTokens,
            new \DateTimeImmutable(date('Y-m-d h:i:sa')),
            accepted: !$this->isOverLimit,
            limit: 1000
        );
    }

    public function consume(int $tokens = 1): RateLimit
    {
        return $this->getFakedRateLimit();
    }

    public function reset(): void
    {
        // noop
    }
}
