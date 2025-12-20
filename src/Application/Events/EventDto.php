<?php

declare(strict_types=1);

namespace App\Application\Events;

final class EventDto
{
    public function __construct(
        public readonly string $type,
        public readonly string $format,
        public readonly string $data,
        public readonly ?string $userEmail = null,
    ) {
    }
}
