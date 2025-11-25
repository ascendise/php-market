<?php

declare(strict_types=1);

namespace App\Application\HAL;

final class Link
{
    public function __construct(
        public readonly string $href,
    ) {
    }
}
