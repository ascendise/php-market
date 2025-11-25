<?php

declare(strict_types=1);

namespace App\Tests\Application\HAL;

use App\Application\HAL\HALResource;
use App\Application\HAL\Link;
use App\Application\HAL\RestLinksProvider;
use App\Application\HAL\WebLinksProvider;

/**
 * @implements \IteratorAggregate<int,StubResource>
 */
final class StubResourceArray extends HALResource implements \IteratorAggregate, WebLinksProvider, RestLinksProvider
{
    /**
     * @var array<int, StubResource>
     */
    private readonly array $resources;

    public function __construct(StubResource ...$resources)
    {
        $this->resources = iterator_to_array($resources);
    }

    public function getWebLinks(): array
    {
        return ['self' => new Link('https://example.com/stubarray')];
    }

    public function getRestLinks(): array
    {
        return ['self' => new Link('https://example.com/stubarray')];
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->resources);
    }
}
