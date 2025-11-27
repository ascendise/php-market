<?php

declare(strict_types=1);

namespace App;

use App\Application\Bots\SymfonyScheduler;
use App\Domain\Bots\Schedule\SchedulePlanner;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\Schedule as SymfonySchedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsSchedule]
class Schedule implements ScheduleProviderInterface
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly SchedulePlanner $planner,
    ) {
    }

    public function getSchedule(): SymfonySchedule
    {
        $schedule = new SymfonySchedule()
            ->stateful($this->cache) // ensure missed tasks are executed
            ->processOnlyLastMissedRun(true); // ensure only last missed task is run
        $scheduler = new SymfonyScheduler($schedule);
        $this->planner->plan($scheduler);

        return $scheduler->getSchedule();
    }
}
