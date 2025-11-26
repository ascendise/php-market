<?php

declare(strict_types=1);

namespace App\Application\Bots;

class CreateBotBlueprint
{
    /**
     * @param array<int,mixed> $args
     */
    public function __construct(
        private readonly string $type,
        private readonly array $args,
        private readonly \DateInterval $frequency,
    ) {
    }

    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return array<int,mixed>
     */
    public function args(): array
    {
        return $this->args;
    }

    public function frequency(): \DateInterval
    {
        return $this->frequency;
    }
}
