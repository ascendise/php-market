<?php

declare(strict_types=1);

namespace App\Domain\Bots\Schedule;

enum InvalidBlueprintError: int
{
    case UnknownType = 1;
    case InvalidArgs = 2;
}
