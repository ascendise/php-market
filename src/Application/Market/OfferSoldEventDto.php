<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\OfferSoldEvent;
use Symfony\Component\Uid\Uuid;

final class OfferSoldEventDto
{
    public function __construct(
        public readonly Uuid $soldOffer,
    ) {
    }

    public static function fromEntity(OfferSoldEvent $event): self
    {
        $offerId = Uuid::fromString($event->soldOffer()->id());

        return new OfferSoldEventDto($offerId);
    }
}
