<?php

declare(strict_types=1);

namespace App\Application\Events;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.transform')]
abstract class Transform
{
    public function __construct(protected readonly string $supportedType)
    {
    }

    /** Returns the type this transformer can convert from */
    public function supportedType(): string
    {
        return $this->supportedType;
    }

    /** @throws \InvalidArgumentException if $event is not a supported type */
    public function transform(mixed $event): EventDto
    {
        $type = $event::class;
        if ($type !== $this->supportedType) {
            throw new \InvalidArgumentException("Expected event '$this->supportedType' got '$type'");
        }

        return $this->transformEvent($event);
    }

    abstract protected function transformEvent(mixed $event): EventDto;
}
