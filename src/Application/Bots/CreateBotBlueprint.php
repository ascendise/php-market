<?php

declare(strict_types=1);

namespace App\Application\Bots;

class CreateBotBlueprint
{
    /**
     * @param array<int,mixed> $botArgs
     */
    public function __construct(
        private readonly string $type,
        private readonly array $botArgs,
        private readonly string|int|\DateInterval $frequency,
    ) {
    }

    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return array<int,mixed>
     */
    public function botArgs(): array
    {
        return $this->botArgs;
    }

    public function frequency(): string|int|\DateInterval
    {
        return $this->frequency;
    }
}
