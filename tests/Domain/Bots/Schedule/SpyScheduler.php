<?php

declare(strict_types=1);

namespace App\Tests\Domain\Bots\Schedule;

use App\Domain\Bots\Schedule\BotBlueprint;
use App\Domain\Bots\Schedule\Scheduler;
use PHPUnit\Framework\TestCase;

final class SpyScheduler implements Scheduler
{
    /** @var array<int, BotBlueprint> */
    private array $scheduledBlueprints = [];

    public function add(BotBlueprint $blueprint): void
    {
        $this->scheduledBlueprints[] = $blueprint;
    }

    /**
     * @param array<int,BotBlueprint> $expectedBlueprints
     */
    public function assertScheduleEquals(array $expectedBlueprints, TestCase $testCase): void
    {
        $testCase->assertEquals($expectedBlueprints, $this->scheduledBlueprints);
    }
}
