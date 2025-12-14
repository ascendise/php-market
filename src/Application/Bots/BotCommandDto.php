<?php

declare(strict_types=1);

namespace App\Application\Bots;

use App\Domain\Bots\Schedule\BotBlueprintCommand;

final class BotCommandDto
{
    public function __construct(
        public readonly BotType $type,
        public readonly mixed $args,
        public readonly FrequencyDto $frequency,
    ) {
    }

    public function toEntity(): BotBlueprintCommand
    {
        return new BotBlueprintCommand($this->type->value, $this->args, $this->frequency->toDateInterval());
    }
}
