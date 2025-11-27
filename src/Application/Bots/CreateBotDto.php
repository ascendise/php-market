<?php

declare(strict_types=1);

namespace App\Application\Bots;

final class CreateBotDto
{
    /** @param array<int, mixed> $args */
    public function __construct(
        public readonly BotType $type,
        public readonly array $args,
        public readonly \DateInterval $frequency,
    ) {
    }
}
