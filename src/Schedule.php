<?php

declare(strict_types=1);

namespace App;

use App\Application\Bots\RunBotsMessage;
use App\Domain\Bots\Schedule\BotBlueprintRepository;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule as SymfonySchedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsSchedule]
class Schedule implements ScheduleProviderInterface
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly BotBlueprintRepository $blueprintRepo,
    ) {
    }

    public function getSchedule(): SymfonySchedule
    {
        $schedule = new SymfonySchedule();
        foreach ($this->blueprintRepo->list() as $blueprint) {
            $trigger = RecurringMessage::every($blueprint->frequency(), new RunBotsMessage([$blueprint]));
            $schedule->add($trigger);
        }

        return $schedule
            ->stateful($this->cache) // ensure missed tasks are executed
            ->processOnlyLastMissedRun(true); // ensure only last missed task is run
    }
}
