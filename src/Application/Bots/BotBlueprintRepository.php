<?php

declare(strict_types=1);

namespace App\Application\Bots;

interface BotBlueprintRepository
{
    /**
     * @return array<int, BotBlueprint>
     */
    public function getAll(): array;

    public function create(CreateBotBlueprint $blueprint): BotBlueprint;

    public function update(string $id, CreateBotBlueprint $blueprint): BotBlueprint;

    public function delete(string $id): BotBlueprint;
}
