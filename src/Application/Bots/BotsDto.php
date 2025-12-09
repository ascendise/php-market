<?php

declare(strict_types=1);

namespace App\Application\Bots;

use App\Application\HAL\HALResource;
use App\Application\HAL\Link;
use App\Application\HAL\RestLinksProvider;
use App\Application\HAL\WebLinksProvider;

/**
 * @implements \IteratorAggregate<int,BotDto>
 */
final class BotsDto extends HALResource implements \IteratorAggregate, WebLinksProvider, RestLinksProvider
{
    /** @param array<int, BotDto> $bots */
    public function __construct(private readonly array $bots)
    {
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->bots);
    }

    public function getWebLinks(): array
    {
        return ['bots' => new Link('/admin/bots')];
    }

    public function getRestLinks(): array
    {
        return ['bots' => new Link('/api/admin/bots')];
    }
}
