<?php

declare(strict_types=1);

namespace App\Domain\Bots\Schedule;

interface BotBlueprintRepository
{
    /**
     * @return array<int, BotBlueprint>
     */
    public function list(): array;

    public function findById(string $id): ?BotBlueprint;

    public function create(BotBlueprintCommand $blueprint): BotBlueprint;

    public function update(string $id, BotBlueprintCommand $blueprint): ?BotBlueprint;

    public function delete(string $id): void;
}
