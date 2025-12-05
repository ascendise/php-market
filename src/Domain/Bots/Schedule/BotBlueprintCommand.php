<?php

declare(strict_types=1);

namespace App\Domain\Bots\Schedule;

class BotBlueprintCommand
{
    public function __construct(
        private readonly string $type,
        private readonly mixed $args,
        private readonly \DateInterval $frequency,
    ) {
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

    public function toBlueprint(string $id): BotBlueprint
    {
        return new BotBlueprint($id, $this->type, $this->args, $this->frequency);
    }
}
