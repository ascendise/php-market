<?php

declare(strict_types=1);

namespace App\Application\Bots;

use App\Domain\Bots\Schedule\BotBlueprint;
use App\Domain\Bots\Schedule\BotBlueprintRepository;
use Symfony\Component\Uid\Uuid;

final class BotAdministrationServiceImpl implements BotAdministrationService
{
    public function __construct(private readonly BotBlueprintRepository $blueprintRepository)
    {
    }

    public function list(): BotsDto
    {
        $blueprints = $this->blueprintRepository->list();
        $blueprints = array_map(fn (BotBlueprint $bp) => BotDto::fromEntity($bp), $blueprints);

        return new BotsDto($blueprints);
    }

    public function create(CreateBotDto $createBot): BotDto
    {
        $newBlueprint = $createBot->toEntity();
        $newBlueprint = $this->blueprintRepository->create($newBlueprint);

        return BotDto::fromEntity($newBlueprint);
    }

    public function update(Uuid $id, CreateBotDto $updateBot): ?BotDto
    {
        $updatedBlueprint = $updateBot->toEntity();
        $updatedBlueprint = $this->blueprintRepository->update($id->toString(), $updatedBlueprint);
        if (!$updatedBlueprint) {
            return null;
        }

        return BotDto::fromEntity($updatedBlueprint);
    }

    public function delete(Uuid $id): void
    {
        $this->blueprintRepository->delete($id->toString());
    }
}
