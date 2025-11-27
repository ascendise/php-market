<?php

declare(strict_types=1);

namespace App\Application\Bots;

use App\Application\HAL\HALResource;
use App\Application\HAL\Link;
use App\Application\HAL\RestLinksProvider;
use App\Application\HAL\WebLinksProvider;
use Symfony\Component\Uid\Uuid;

final class BotDto extends HALResource implements WebLinksProvider, RestLinksProvider
{
    /** @param array<int, mixed> $args */
    public function __construct(
        public readonly Uuid $id,
        public readonly BotType $type,
        public readonly array $args,
        public readonly \DateInterval $frequency,
    ) {
    }

    public function getWebLinks(): array
    {
        return [
            'self' => new Link("/admin/bots/$this->id"),
            'bots' => new Link('/admin/bots'),
        ];
    }

    public function getRestLinks(): array
    {
        return [
            'self' => new Link("/api/admin/bots/$this->id"),
            'bots' => new Link('/api/admin/bots'),
        ];
    }
}
