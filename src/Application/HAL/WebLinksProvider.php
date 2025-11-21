<?php

declare(strict_types=1);

namespace App\Application\HAL;

/**
 * Provides links to the resource using the Web UI.
 */
interface WebLinksProvider
{
    /**
     * @return array<string, Link>
     */
    public function getWebLinks(): array;
}
