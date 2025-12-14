<?php

declare(strict_types=1);

namespace App\Domain\Bots\Schedule;

interface BotBlueprintValidator
{
    /**
     * Checks if a blueprint has valid arguments specified.
     * Required because args are mixed and therefore blueprints
     * with missing/invalid args could be received.
     *
     * returns InvalidBlueprintException containing detailed error or
     * null if blueprint is valid
     */
    public function isValid(BotBlueprint $blueprint): ?InvalidBlueprintException;
}
