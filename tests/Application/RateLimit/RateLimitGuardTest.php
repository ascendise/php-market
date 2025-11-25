<?php

declare(strict_types=1);

namespace App\Tests\Application\RateLimit;

use App\Application\RateLimit\RateLimitGuard;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\LimiterInterface;

final class RateLimitGuardTest extends TestCase
{
    private function setupSut(LimiterInterface $limiter): RateLimitGuard
    {
        $rateLimiters = new MemoryRateLimiterFactory($limiter);

        return new RateLimitGuard($rateLimiters);
    }

    public function testGuardShouldRunClosureAndReturnResponseBelowLimit(): void
    {
        // Arrange
        $sut = $this->setupSut(new StubLimiter(isOverLimit: false));
        // Act
        $response = $sut->guard(
            function () {
                return new Response();
            },
            'user'
        );
        // Assert
        $this->assertEquals(200, $response->getStatusCode(), 'Request was rejected!');
    }

    public function testGuardShouldReturnTooManyRequestsResponseOverLimit(): void
    {
        // Arrange
        $sut = $this->setupSut(new StubLimiter(isOverLimit: true));
        // Act
        $response = $sut->guard(
            function () {
                return new Response();
            },
            'user'
        );
        // Assert
        $this->assertEquals(429, $response->getStatusCode(), 'Request was accepted despite being over limit!');
    }
}
