<?php

declare(strict_types=1);

namespace App\Tests\Application\RateLimit;

use Symfony\Component\RateLimiter\LimiterInterface;
use Symfony\Component\RateLimiter\RateLimiterFactoryInterface;

final class MemoryRateLimiterFactory implements RateLimiterFactoryInterface
{
    public function __construct(private readonly LimiterInterface $rateLimiter)
    {
    }

    public function create(?string $key = null): LimiterInterface
    {
        return $this->rateLimiter;
    }
}
