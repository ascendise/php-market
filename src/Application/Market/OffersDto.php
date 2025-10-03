<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\Offer;
use App\Domain\Market\Offers;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int,Offer>
 */
final class OffersDto implements IteratorAggregate
{
    private array $offers = [];

    public function __construct(OfferDto ...$offers)
    {
        $this->offers = $offers ?? [];
    }

    public static function fromEntity(Offers $offers): OffersDto
    {
        $offerDtos = array_map(fn (Offer $o) => OfferDto::fromEntity($o), iterator_to_array($offers));
        return new OffersDto(...$offerDtos);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->offers);
    }
}
