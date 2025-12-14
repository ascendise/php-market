<?php

declare(strict_types=1);

namespace App\Tests\Domain\Bots\Schedule;

use App\Domain\Bots\Schedule\BotBlueprint;
use App\Domain\Bots\Schedule\SchedulePlanner;
use PHPUnit\Framework\TestCase;

final class SchedulePlannerTest extends TestCase
{
    /**
     * @param array<int,BotBlueprint> $blueprints
     */
    public function setupSut(array $blueprints): SchedulePlanner
    {
        $blueprintRepo = new MemoryBlueprintRepository(...$blueprints);

        return new SchedulePlanner($blueprintRepo);
    }

    public function testPlanShouldCreateScheduleFromStoredBlueprints(): void
    {
        // Arrange
        $blueprints = [
            new BotBlueprint('1', 'Stub', [], \DateInterval::createFromDateString('1 second')),
            new BotBlueprint('2', 'Stub', [], \DateInterval::createFromDateString('5 second')),
            new BotBlueprint('3', 'Stub', [['hello' => 'dev!']], \DateInterval::createFromDateString('10 second')),
        ];
        $sut = $this->setupSut($blueprints);
        // Act
        $spySchedule = new SpyScheduler();
        $sut->plan($spySchedule);
        // Assert
        $spySchedule->assertScheduleEquals($blueprints, $this);
    }
}
