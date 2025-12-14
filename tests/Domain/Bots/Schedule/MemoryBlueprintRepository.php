<?php

declare(strict_types=1);

namespace App\Tests\Domain\Bots\Schedule;

use App\Domain\Bots\Schedule\BotBlueprint;
use App\Domain\Bots\Schedule\BotBlueprintCommand;
use App\Domain\Bots\Schedule\BotBlueprintRepository;
use Symfony\Component\Uid\Uuid;

final class MemoryBlueprintRepository implements BotBlueprintRepository
{
    /** @var array<string, BotBlueprint> */
    private array $blueprints = [];

    public function __construct(BotBlueprint ...$blueprints)
    {
        foreach ($blueprints as $blueprint) {
            $this->blueprints += [$blueprint->id() => $blueprint];
        }
    }

    public function list(): array
    {
        return array_values($this->blueprints);
    }

    public function findById(string $id): ?BotBlueprint
    {
        if (!isset($this->blueprints[$id])) {
            return null;
        }

        return $this->blueprints[$id];
    }

    public function create(BotBlueprintCommand $blueprint): BotBlueprint
    {
        $blueprint = new BotBlueprint(
            Uuid::v7()->toString(),
            $blueprint->type(),
            $blueprint->args(),
            $blueprint->frequency()
        );
        $this->blueprints += [$blueprint->id() => $blueprint];

        return $blueprint;
    }

    public function update(string $id, BotBlueprintCommand $blueprint): ?BotBlueprint
    {
        if (!array_key_exists($id, $this->blueprints)) {
            return null;
        }
        $blueprint = $blueprint->toBlueprint(Uuid::v7()->toString());
        $this->blueprints += [$blueprint->id() => $blueprint];

        return $blueprint;
    }

    public function delete(string $id): void
    {
        unset($this->blueprints[$id]);
    }
}
