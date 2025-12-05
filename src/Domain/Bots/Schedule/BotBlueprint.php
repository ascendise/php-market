<?php

declare(strict_types=1);

namespace App\Domain\Bots\Schedule;

final class BotBlueprint
{
    public function __construct(
        private readonly string $id,
        private readonly string $type,
        private readonly mixed $args,
        private readonly \DateInterval $frequency,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function args(): mixed
    {
        return $this->args;
    }

    public function frequency(): \DateInterval
    {
        return $this->frequency;
    }
}
