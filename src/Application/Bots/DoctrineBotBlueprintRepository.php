<?php

declare(strict_types=1);

namespace App\Application\Bots;

use App\Domain\Bots\Schedule\BotBlueprint;
use App\Domain\Bots\Schedule\BotBlueprintRepository;
use App\Domain\Bots\Schedule\CreateBotBlueprint;
use App\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class DoctrineBotBlueprintRepository implements BotBlueprintRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function list(): array
    {
        $blueprints = $this->blueprintRepo()->findAll();

        return array_map(fn ($bp) => $bp->toEntity(), $blueprints);
    }

    /**
     * @return EntityRepository<Entity\BotBlueprint>
     */
    private function blueprintRepo(): EntityRepository
    {
        return $this->entityManager->getRepository(Entity\BotBlueprint::class);
    }

    public function create(CreateBotBlueprint $createBlueprint): BotBlueprint
    {
        $blueprint = new Entity\BotBlueprint();
        $blueprint = $this->updateFrom($blueprint, $createBlueprint);
        $this->entityManager->persist($blueprint);
        $this->entityManager->flush();

        return $blueprint->toEntity();
    }

    private function updateFrom(Entity\BotBlueprint $target, CreateBotBlueprint $source): Entity\BotBlueprint
    {
        return $target->setType($source->type())
            ->setArgs($source->args())
            ->setFrequency($source->frequency());
    }

    public function update(string $id, CreateBotBlueprint $blueprint): ?BotBlueprint
    {
        $old = $this->blueprintRepo()->find($id);
        if (!$old) {
            return null;
        }
        $updated = $this->updateFrom($old, $blueprint);
        $this->entityManager->flush();

        return $updated->toEntity();
    }

    public function delete(string $id): void
    {
        $blueprint = $this->blueprintRepo()->find($id);
        if (!$blueprint) {
            return;
        }
        $this->entityManager->remove($blueprint);
    }
}
