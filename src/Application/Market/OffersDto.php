<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\Offer;
use App\Domain\Market\Offers;

/**
 * @implements \IteratorAggregate<int,OfferDto>
 */
final class OffersDto implements \IteratorAggregate
{
    /**
     * @var array<string, OffersDto>
     */
    private array $offers = [];

    public function __construct(OfferDto ...$offers)
    {
        foreach ($offers as $offer) {
            $this->offers += [$offer->id->toString() => $offer];
        }
    }

    public static function fromEntity(Offers $offers): OffersDto
    {
        $offerDtos = array_map(fn (Offer $o) => OfferDto::fromEntity($o), iterator_to_array($offers));

        return new OffersDto(...$offerDtos);
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->offers);
    }
}
