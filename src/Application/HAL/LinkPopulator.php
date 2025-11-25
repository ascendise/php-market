<?php

declare(strict_types=1);

namespace App\Application\HAL;

final class LinkPopulator
{
    public function populateRestLinks(HALResource $resource): HALResource
    {
        if ($resource instanceof RestLinksProvider) {
            $resource->setHalLinks($resource->getRestLinks());
        }
        $children = $this->findChildResources($resource);
        foreach ($children as $child) {
            $this->populateRestLinks($child);
        }

        return $resource;
    }

    /**
     * @return \Generator<HALResource>
     */
    private function findChildResources(mixed $resource): \Generator
    {
        foreach ((array) $resource as $child) {
            if ($child instanceof HALResource) {
                yield $child;
                yield from $this->findChildResources($child);
            }
            if (is_array($child)) {
                yield from $this->findChildResources($child);
            }
        }
    }

    public function populateWebLinks(HALResource $resource): HALResource
    {
        if ($resource instanceof WebLinksProvider) {
            $resource->setHalLinks($resource->getWebLinks());
        }
        $children = $this->findChildResources($resource);
        foreach ($children as $child) {
            $this->populateWebLinks($child);
        }

        return $resource;
    }
}
