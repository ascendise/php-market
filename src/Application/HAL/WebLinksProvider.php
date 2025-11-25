<?php

declare(strict_types=1);

namespace App\Application\HAL;

use Symfony\Component\Serializer\Attribute\Ignore;

/**
 * Provides links to the resource using the Web UI.
 */
interface WebLinksProvider
{
    /**
     * @return array<string, Link>
     */
    #[Ignore]
    public function getWebLinks(): array;
}
