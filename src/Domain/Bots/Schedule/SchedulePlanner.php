<?php

declare(strict_types=1);

namespace App\Domain\Bots\Schedule;

final class SchedulePlanner
{
    public function __construct(private readonly BotBlueprintRepository $blueprintRepo)
    {
    }

    public function plan(Scheduler $scheduler): Scheduler
    {
        $blueprints = $this->blueprintRepo->list();
        foreach ($blueprints as $blueprint) {
            $scheduler->add($blueprint);
        }

        return $scheduler;
    }
}
