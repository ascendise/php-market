<?php

declare(strict_types=1);

namespace App\Application\HAL;

final class LinkPopulator
{
    public function populateRestLinks(HALResource $resource): HALResource
    {
        if (!$resource instanceof RestLinksProvider) {
            throw new \InvalidArgumentException('$resource does not implement RestLinksProvider!');
        }
        $resource->setHalLinks($resource->getRestLinks());

        return $resource;
    }

    public function populateWebLinks(HALResource $resource): HALResource
    {
        if (!$resource instanceof WebLinksProvider) {
            throw new \InvalidArgumentException('$resource does not implement WebLinksProvider!');
        }
        $resource->setHalLinks($resource->getWebLinks());

        return $resource;
    }
}
