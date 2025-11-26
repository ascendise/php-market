<?php

declare(strict_types=1);

namespace App\Application\Bots;

final class BotBlueprint
{
    /**
     * @param array<int,mixed> $args
     */
    public function __construct(
        private readonly string $id,
        private readonly string $type,
        private readonly array $args,
        private readonly string|int|\DateInterval $frequency,
    ) {
    }

    public function id(): string
    {
        return $this->id;
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

    public function frequency(): string|int|\DateInterval
    {
        return $this->frequency;
    }
}
