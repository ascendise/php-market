<?php

namespace App\Application\Bots;

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
        $id = $blueprint->id();

        return match ($id) {
            'producer' => new Producer($this->market, $blueprint->args()[0], new RNG()),
            'consumer' => new Consumer($this->market, $blueprint->args()[0], new RNG()),
            default => throw new \InvalidArgumentException("Unknown blueprint id: $id"),
        };
    }
}
