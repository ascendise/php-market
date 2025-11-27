<?php

declare(strict_types=1);

namespace App\Application\Bots;

use App\Domain\Bots\Consumer;
use App\Domain\Bots\Producer;

enum BotType: string
{
    case Producer = Producer::class;
    case Consumer = Consumer::class;
}
