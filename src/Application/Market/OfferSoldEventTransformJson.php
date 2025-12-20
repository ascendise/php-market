<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Application\Events\EventDto;
use App\Application\Events\Transform;
use App\Domain\Market\OfferSoldEvent;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem]
final class OfferSoldEventTransformJson extends Transform
{
    public function __construct()
    {
        parent::__construct(OfferSoldEvent::class);
    }

    #[\Override]
    protected function transformEvent(mixed $event): EventDto
    {
        $event = OfferSoldEventDto::fromEntity($event);

        return new EventDto(OfferSoldEvent::class, 'json', json_encode($event));
    }
}
