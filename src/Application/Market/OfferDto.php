<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Application\HAL\HALResource;
use App\Application\HAL\Link;
use App\Application\HAL\RestLinksProvider;
use App\Application\HAL\WebLinksProvider;
use App\Domain\Market\Offer;
use App\Domain\Market\Seller;
use Symfony\Component\Uid\Uuid;

final class OfferDto extends HALResource implements WebLinksProvider, RestLinksProvider
{
    public function __construct(
        public readonly Uuid $id,
        public readonly ProductDto $product,
        public readonly int $quantity,
        public readonly int $totalPrice,
        public readonly string $sellerId,
    ) {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity cannot be less than zero!');
        }
        if ($totalPrice <= 0) {
            throw new \InvalidArgumentException('Price cannot be less than zero!');
        }
    }

    public static function fromEntity(Offer $offer): OfferDto
    {
        return new OfferDto(
            Uuid::fromString($offer->id()),
            ProductDto::fromEntity($offer->product()),
            quantity: $offer->quantity(),
            totalPrice: $offer->totalPrice(),
            sellerId: $offer->seller()->id()
        );
    }

    public function toEntity(Seller $seller): Offer
    {
        return new Offer(
            $this->id->toString(),
            $this->product->toEntity(),
            pricePerItem: $this->pricePerItem(),
            quantity: $this->quantity,
            seller: $seller
        );
    }

    public function pricePerItem(): int
    {
        return $this->totalPrice / $this->quantity;
    }

    public function getWebLinks(): array
    {
        return ['buy' => new Link("/market/_buy/{$this->id}")];
    }

    public function getRestLinks(): array
    {
        return ['buy' => new Link("/api/market/buy/{$this->id}")];
    }
}
