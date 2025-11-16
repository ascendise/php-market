<?php

declare(strict_types=1);

namespace App\Tests\Domain\Bots;

use App\Domain\Bots\BotRunner;
use App\Tests\SpyLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

final class BotRunnerTest extends TestCase
{
    public function testActShouldRunAllBots(): void
    {
        // Arrange
        $sut = new BotRunner(new NullLogger());
        $bots = [
            new SpyBot(),
            new SpyBot(),
            new SpyBot(),
        ];
        // Act
        $sut->run($bots);
        // Assert
        $bots[0]->assertHasRunOnce($this);
        $bots[1]->assertHasRunOnce($this);
        $bots[2]->assertHasRunOnce($this);
    }

    public function testActShouldLogOnFailAndContinue(): void
    {
        // Arrange
        $logger = new SpyLogger();
        $sut = new BotRunner($logger);
        $bots = [
            new ErroringBot(),
            new SpyBot(),
            new SpyBot(),
        ];
        // Act
        $sut->run($bots);
        // Assert
        $this->assertCount(1, $logger->logs());
        $this->assertEquals(LogLevel::ERROR, $logger->logs()[0]->level());
        $bots[1]->assertHasRunOnce($this);
        $bots[2]->assertHasRunOnce($this);
    }
}
