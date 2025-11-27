<?php

declare(strict_types=1);

namespace App\Application\Bots;

use App\Application\HAL\HALResource;

/**
 * @implements \IteratorAggregate<int,BotDto>
 */
final class BotsDto extends HALResource implements \IteratorAggregate
{
    /** @param array<int, BotDto> $bots */
    public function __construct(private readonly array $bots)
    {
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->bots);
    }
}
