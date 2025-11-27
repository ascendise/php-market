<?php

declare(strict_types=1);

namespace App\Domain\Bots\Schedule;

interface BotBlueprintRepository
{
    /**
     * @return array<int, BotBlueprint>
     */
    public function list(): array;

    public function create(CreateBotBlueprint $blueprint): BotBlueprint;

    public function update(string $id, CreateBotBlueprint $blueprint): ?BotBlueprint;

    public function delete(string $id): void;
}
