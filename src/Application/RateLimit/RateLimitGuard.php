<?php

declare(strict_types=1);

namespace App\Application\RateLimit;

use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactoryInterface;

final class RateLimitGuard
{
    public function __construct(
        #[Target('main.limiter')] private readonly RateLimiterFactoryInterface $apiLimiter,
    ) {
    }

    public function guard(\Closure $closure, string $key): Response
    {
        $limiter = $this->apiLimiter->create($key);
        if (false === $limiter->consume(1)->isAccepted()) {
            return new Response('Rate limit reached!', Response::HTTP_TOO_MANY_REQUESTS);
        }

        return $closure();
    }
}
