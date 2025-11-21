<?php

declare(strict_types=1);

namespace App\Application\HAL;

/**
 * Provides links to the resource using the REST API.
 */
interface RestLinksProvider
{
    /**
     * @return array<string, Link>
     */
    public function getRestLinks(): array;
}
