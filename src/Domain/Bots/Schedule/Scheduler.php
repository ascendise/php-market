<?php

declare(strict_types=1);

namespace App\Domain\Bots\Schedule;

interface Scheduler
{
    public function add(BotBlueprint $blueprint): void;
}
