<?php

declare(strict_types=1);

namespace App\Domain\Bots\Schedule;

use App\Domain\Bots\Bot;
use App\Domain\Bots\Consumer;
use App\Domain\Bots\Producer;
use App\Domain\Bots\RNG;
use App\Domain\Market\Market;

final class BotFactory implements BotBlueprintValidator
{
    public function __construct(private readonly Market $market)
    {
    }

    public function create(BotBlueprint $blueprint): Bot
    {
        $type = $blueprint->type();
        $result = $this->tryCreateBot($blueprint);
        if ($result instanceof InvalidBlueprintException) {
            throw $result;
        }

        return $result;
    }

    public function isValid(BotBlueprint $blueprint): ?InvalidBlueprintException
    {
        $result = $this->tryCreateBot($blueprint);
        if ($result instanceof InvalidBlueprintException) {
            return $result;
        }

        return null;
    }

    private function tryCreateBot(BotBlueprint $blueprint): Bot|InvalidBlueprintException
    {
        $type = $blueprint->type();

        try {
            return match ($type) {
                Producer::class => new Producer($this->market, $blueprint->args(), new RNG()),
                Consumer::class => new Consumer($this->market, $blueprint->args(), new RNG()),
                default => new InvalidBlueprintException($blueprint, InvalidBlueprintError::UnknownType),
            };
        } catch (\TypeError $e) {
            return new InvalidBlueprintException($blueprint, InvalidBlueprintError::InvalidArgs, $e);
        }
    }
}
