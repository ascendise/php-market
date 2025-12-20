<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Application\Events\EventDto;
use App\Application\Events\Transform;
use App\Application\HAL\LinkPopulator;
use App\Domain\Market\OfferCreatedEvent;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Twig\Environment;

#[AsTaggedItem]
final class OfferCreatedEventTransformHtml extends Transform
{
    public function __construct(
        private readonly Environment $twig,
        private readonly LinkPopulator $linkPopulator,
    ) {
        parent::__construct(OfferCreatedEvent::class);
    }

    #[\Override]
    protected function transformEvent(mixed $event): EventDto
    {
        $event = OfferCreatedEventDto::fromEntity($event);
        $offer = $this->linkPopulator->populateWebLinks($event->newOffer);
        $view = $this->twig->render('market/_offer.html.twig', ['offer' => $offer]);
        $view = str_replace("\n", '', $view);

        return new EventDto(OfferCreatedEvent::class, 'html', $view);
    }
}
