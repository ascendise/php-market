<?php

declare(strict_types=1);

namespace App\Application\Events;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

final class EventTransformer
{
    /** @var array<string, array<int, Transform>> */
    private readonly array $transforms;

    /**
     * @param iterable<int,Transform> $transforms
     */
    public function __construct(#[AutowireIterator('app.transform')] iterable $transforms)
    {
        $sortedTransforms = [];
        foreach ($transforms as $transform) {
            $type = $transform->supportedType();
            if (!isset($sortedTransforms[$type])) {
                $sortedTransforms[$type] = [];
            }
            $sortedTransforms[$type][] = $transform;
        }
        $this->transforms = $sortedTransforms;
    }

    /** @return array<string, EventDto> */
    public function transform(mixed $event): array
    {
        $events = [];
        foreach ($this->transforms[$event::class] as $transform) {
            $event = $transform->transform($event);
            $events[$event->format] = $event;
        }

        return $events;
    }
}
