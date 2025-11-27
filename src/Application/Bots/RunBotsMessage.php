<?php

declare(strict_types=1);

namespace App\Application\Bots;

use App\Domain\Bots\Schedule\BotBlueprint;

final class RunBotsMessage
{
    /**
     * @param array<int,BotBlueprint> $botsToRun
     */
    public function __construct(
        private readonly array $botsToRun,
    ) {
    }

    /**
     * @return array<int,BotBlueprint>
     */
    public function botsToRun(): array
    {
        return $this->botsToRun;
    }
}
