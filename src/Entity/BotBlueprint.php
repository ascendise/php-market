<?php

namespace App\Entity;

use App\Domain;
use App\Repository\BotBlueprintRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV7;

#[ORM\Entity(repositoryClass: BotBlueprintRepository::class)]
class BotBlueprint
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?UuidV7 $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: 'object')]
    private mixed $args = [];

    #[ORM\Column]
    private ?\DateInterval $frequency = null;

    public static function fromEntity(Domain\Bots\Schedule\BotBlueprint $entity): BotBlueprint
    {
        $blueprint = new BotBlueprint();
        $blueprint->id = UuidV7::fromString($entity->id());
        $blueprint->setType($entity->type());
        $blueprint->setArgs($entity->args());
        $blueprint->setFrequency($entity->frequency());

        return $blueprint;
    }

    public function toEntity(): Domain\Bots\Schedule\BotBlueprint
    {
        return new Domain\Bots\Schedule\BotBlueprint(
            $this->getId(),
            $this->getType(),
            $this->getArgs(),
            $this->getFrequency()
        );
    }

    public function getId(): ?UuidV7
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function setArgs(mixed $args): static
    {
        $this->args = $args;

        return $this;
    }

    public function getArgs(): mixed
    {
        return $this->args;
    }

    public function getFrequency(): ?\DateInterval
    {
        return $this->frequency;
    }

    public function setFrequency(\DateInterval $frequency): static
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function update(Domain\Bots\Schedule\BotBlueprintCommand $updateBlueprint): static
    {
        return $this->setType($updateBlueprint->type())
            ->setArgs($updateBlueprint->args())
            ->setFrequency($updateBlueprint->frequency());
    }
}
