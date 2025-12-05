<?php

declare(strict_types=1);

namespace App\Domain\Bots\Schedule;

use App\Domain\Bots\Bot;
use App\Domain\Bots\Consumer;
use App\Domain\Bots\Producer;
use App\Domain\Bots\RNG;
use App\Domain\Market\Market;

final class BotFactory
{
    public function __construct(private readonly Market $market)
    {
    }

    public function create(BotBlueprint $blueprint): Bot
    {
        $type = $blueprint->type();
        var_dump($blueprint->args());

        return match ($type) {
            Producer::class => new Producer($this->market, $blueprint->args(), new RNG()),
            Consumer::class => new Consumer($this->market, $blueprint->args(), new RNG()),
            default => throw new \InvalidArgumentException("Unknown blueprint type: $type"),
        };
    }
}
