<?php

declare(strict_types=1);

namespace App\Application\HAL;

final class Link
{
    public function __construct(
        private readonly string $href,
    ) {
    }

    public function href(): string
    {
        return $this->href;
    }
}
