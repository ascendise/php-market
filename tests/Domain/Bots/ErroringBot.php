<?php

declare(strict_types=1);

namespace App\Tests\Domain\Bots;

use App\Domain\Bots\Bot;

final class ErroringBot implements Bot
{
    public function act(): void
    {
        throw new \Exception('Error: ErroringBot works as intended');
    }
}
