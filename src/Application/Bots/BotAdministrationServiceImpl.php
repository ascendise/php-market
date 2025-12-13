<?php

declare(strict_types=1);

namespace App\Application\Bots;

use App\Domain\Bots\Schedule\BotBlueprint;
use App\Domain\Bots\Schedule\BotBlueprintCommand;
use App\Domain\Bots\Schedule\BotBlueprintRepository;
use App\Domain\Bots\Schedule\BotBlueprintValidator;
use Symfony\Component\Uid\Uuid;

final class BotAdministrationServiceImpl implements BotAdministrationService
{
    public function __construct(
        private readonly BotBlueprintRepository $blueprintRepository,
        private readonly BotBlueprintValidator $blueprintValidator,
    ) {
    }

    public function list(): BotsDto
    {
        $blueprints = $this->blueprintRepository->list();
        $blueprints = array_map(fn (BotBlueprint $bp) => BotDto::fromEntity($bp), $blueprints);

        return new BotsDto($blueprints);
    }

    public function findById(Uuid $botId): ?BotDto
    {
        $blueprint = $this->blueprintRepository->findById($botId->toString());
        if (null === $blueprint) {
            return null;
        }

        return BotDto::fromEntity($blueprint);
    }

    public function create(BotCommandDto $createBot): BotDto
    {
        $newBlueprint = $createBot->toEntity();
        $this->assertValidBlueprint($newBlueprint);
        $newBlueprint = $this->blueprintRepository->create($newBlueprint);

        return BotDto::fromEntity($newBlueprint);
    }

    private function assertValidBlueprint(BotBlueprintCommand $blueprint): void
    {
        $blueprint = $blueprint->toBlueprint('stub');
        $result = $this->blueprintValidator->isValid($blueprint);
        if (null !== $result) {
            throw $result;
        }
    }

    public function update(Uuid $id, BotCommandDto $updateBot): ?BotDto
    {
        $updatedBlueprint = $updateBot->toEntity();
        $this->assertValidBlueprint($updatedBlueprint);
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
