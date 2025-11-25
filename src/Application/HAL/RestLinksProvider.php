<?php

declare(strict_types=1);

namespace App\Application\HAL;

use Symfony\Component\Serializer\Attribute\Ignore;

/**
 * Provides links to the resource using the REST API.
 */
interface RestLinksProvider
{
    /**
     * @return array<string, Link>
     */
    #[Ignore]
    public function getRestLinks(): array;
}
