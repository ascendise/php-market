<?php

declare(strict_types=1);

namespace App\Application\Bots;

use App\Domain\Bots\Schedule\BotBlueprint;
use App\Domain\Bots\Schedule\Scheduler;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;

final class SymfonyScheduler implements Scheduler
{
    public function __construct(private readonly Schedule $schedule)
    {
    }

    public function add(BotBlueprint $blueprint): void
    {
        $trigger = RecurringMessage::every($blueprint->frequency(), new RunBotsMessage([$blueprint]));
        $this->schedule->add($trigger);
    }

    public function getSchedule(): Schedule
    {
        return $this->schedule;
    }
}
