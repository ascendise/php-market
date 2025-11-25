<?php

declare(strict_types=1);

namespace App\Application\HAL;

use Symfony\Component\Serializer\Attribute\SerializedName;

abstract class HALResource
{
    /**
     * @var ?array<string, Link>
     */
    #[SerializedName('_links')]
    private ?array $halLinks = null;

    /**
     * @var ?array<string, HALResource>
     */
    #[SerializedName('_embedded')]
    private ?array $halEmbedded = null;

    /**
     * @return array<string, Link>
     */
    public function halLinks(): ?array
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
