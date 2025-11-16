<?php

declare(strict_types=1);

namespace App\Tests\Domain\Bots;

use App\Domain\Bots\Bot;
use PHPUnit\Framework\TestCase;

final class SpyBot implements Bot
{
    private int $actCount = 0;

    public function act(): void
    {
        ++$this->actCount;
    }

    public function assertHasRun(TestCase $test): void
    {
        $test->assertNotEquals(0, $this->actCount, 'Bot has not been run');
    }

    public function assertHasRunOnce(TestCase $test): void
    {
        $test->assertEquals(1, $this->actCount, "Bot has been run $this->actCount times!");
    }
}
