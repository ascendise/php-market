<?php

declare(strict_types=1);

namespace App\Application\Bots;

use App\Domain\Bots\Schedule\BotBlueprintCommand;

final class BotCommandDto
{
    /** @param array<int, mixed> $args */
    public function __construct(
        public readonly BotType $type,
        public readonly array $args,
        public readonly \DateInterval $frequency,
    ) {
    }

    public function toEntity(): BotBlueprintCommand
    {
        return new BotBlueprintCommand($this->type->value, $this->args, $this->frequency);
    }
}
