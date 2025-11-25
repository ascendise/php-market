<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Application\HAL\HALResource;
use App\Application\HAL\Link;
use App\Application\HAL\RestLinksProvider;
use App\Application\HAL\WebLinksProvider;

final class MarketDto extends HALResource implements WebLinksProvider, RestLinksProvider
{
    public function __construct(public readonly OffersDto $offers)
    {
    }

    public function getWebLinks(): array
    {
        return [
            'offers' => new Link('/market/_offers'),
        ];
    }

    public function getRestLinks(): array
    {
        return [
            'self' => new Link('/api/market/'),
        ];
    }
}
