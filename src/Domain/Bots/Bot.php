<?php

declare(strict_types=1);

namespace App\Domain\Bots;

interface Bot
{
    public function act(): void;
}
