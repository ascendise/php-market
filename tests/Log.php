<?php

declare(strict_types=1);

namespace App\Tests;

final class Log
{
    /**
     * @param array<int,mixed> $context
     */
    public function __construct(
        private readonly mixed $level,
        private readonly string|\Stringable $message,
        private readonly array $context,
    ) {
    }

    public function level(): mixed
    {
        return $this->level;
    }

    public function message(): string|\Stringable
    {
        return $this->message;
    }

    /**
     * @return array<int,mixed>
     */
    public function context(): array
    {
        return $this->context;
    }
}
