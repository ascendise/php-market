<?php

declare(strict_types=1);

namespace App\Application\HAL;

abstract class HALResource
{
    /**
     * @var ?array<string, Link>
     */
    private ?array $halLinks = null;

    /**
     * @var ?array<string, HALResource>
     */
    private ?array $halEmbedded = null;

    /**
     * @SerializedName("_links")
     *
     * @return array<string, Link>
     */
    public function halLinks(): array
    {
        return $this->halLinks;
    }

    /**
     * @param ?array<string, Link> $halLinks
     */
    public function setHalLinks(?array $halLinks): void
    {
        $this->halLinks = $halLinks;
    }

    /**
     * @SerializedName("_embedded")
     *
     * @return array<string, HALResource>
     */
    public function halEmbedded(): ?array
    {
        return $this->halEmbedded;
    }

    /**
     * @param ?array<string, HALResource> $halEmbedded
     */
    public function setHalEmbedded(?array $halEmbedded): void
    {
        $this->halEmbedded = $halEmbedded;
    }
}
