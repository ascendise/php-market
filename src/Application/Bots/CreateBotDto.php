<?php

declare(strict_types=1);

namespace App\Application\Bots;

use App\Domain\Bots\Schedule\CreateBotBlueprint;

final class CreateBotDto
{
    /** @param array<int, mixed> $args */
    public function __construct(
        public readonly BotType $type,
        public readonly array $args,
        public readonly \DateInterval $frequency,
    ) {
    }

    public function toEntity(): CreateBotBlueprint
    {
        return new CreateBotBlueprint($this->type->value, $this->args, $this->frequency);
    }
}
