<?php

declare(strict_types=1);

namespace App\Tests\Application\HAL;

use App\Application\HAL\HALResource;
use App\Application\HAL\Link;
use App\Application\HAL\RestLinksProvider;
use App\Application\HAL\WebLinksProvider;

final class StubResource extends HALResource implements WebLinksProvider, RestLinksProvider
{
    public function __construct(public readonly ?StubResource $child = null)
    {
    }

    public function getWebLinks(): array
    {
        return ['self' => new Link('https://example.com/stub')];
    }

    public function getRestLinks(): array
    {
        return ['self' => new Link('https://example.com/stub')];
    }
}
