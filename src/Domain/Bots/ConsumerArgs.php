<?php

declare(strict_types=1);

namespace App\Domain\Bots;

final class ConsumerArgs
{
    /**
     * @param array<int,ConsumeRate> $consumeRates
     */
    public function __construct(
        private readonly array $consumeRates,
    ) {
    }

    /**
     * @return array<int,ConsumeRate>
     */
    public function consumeRates(): array
    {
        return $this->consumeRates;
    }
}
