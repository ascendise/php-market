<?php

declare(strict_types=1);

namespace App\Domain\Bots;

final class ProducerArgs
{
    /**
     * @param array<int,ProduceRate> $produceRates
     */
    public function __construct(
        private readonly array $produceRates,
    ) {
    }

    /**
     * @return array<int,ProduceRate>
     */
    public function produceRates(): array
    {
        return $this->produceRates;
    }
}
