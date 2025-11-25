<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Application\HAL\HALResource;
use App\Application\HAL\Link;
use App\Application\HAL\RestLinksProvider;
use App\Application\HAL\WebLinksProvider;
use App\Domain\Market\Balance;
use App\Domain\Market\Trader;
use Symfony\Component\Uid\Uuid;

final class TraderDto extends HALResource implements WebLinksProvider, RestLinksProvider
{
    public function __construct(
        public readonly Uuid $id,
        public readonly int $balance,
        public readonly InventoryDto $inventory,
    ) {
        if ($balance < 0) {
            throw new \InvalidArgumentException("Trader can't have balance less than zero!");
        }
    }

    public static function fromEntity(Trader $trader): TraderDto
    {
        return new TraderDto(
            Uuid::fromString($trader->id()),
            $trader->balance(),
            InventoryDto::fromEntities(iterator_to_array($trader->listInventory()))
        );
    }

    public function toEntity(): Trader
    {
        return new Trader(
            $this->id->toString(),
            $this->inventory->toEntity(),
            new Balance($this->balance)
        );
    }

    public function getWebLinks(): array
    {
        return [
            'self' => new Link('/market/_trader'),
            'sell' => new Link('/market/_sell'),
        ];
    }

    public function getRestLinks(): array
    {
        return [
            'sell' => new Link('/api/market/sell'),
            'market' => new Link('/api/market'),
        ];
    }
}
