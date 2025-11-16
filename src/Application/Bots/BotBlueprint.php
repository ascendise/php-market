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
        private readonly array $args,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return array<int,mixed>
     */
    public function args(): array
    {
        return $this->args;
    }
}
