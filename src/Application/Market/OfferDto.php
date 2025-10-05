<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\Offer;
use App\Domain\Market\Seller;
use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

final class OfferDto
{
    public function __construct(
        public readonly Uuid $id,
        public readonly ProductDto $product,
        public readonly int $quantity,
        public readonly int $totalPrice,
        public readonly Uuid $sellerId
    ) {
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantity cannot be less than zero!');
        }
        if ($totalPrice <= 0) {
            throw new InvalidArgumentException('Price cannot be less than zero!');
        }
    }

    public static function fromEntity(Offer $offer): OfferDto
    {
        return new OfferDto(
            Uuid::fromString($offer->id()),
            ProductDto::fromEntity($offer->product()),
            quantity: $offer->quantity(),
            totalPrice: $offer->totalPrice(),
            sellerId: Uuid::fromString($offer->seller()->id())
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
}
