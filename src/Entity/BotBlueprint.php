<?php

namespace App\Entity;

use App\Repository\BotBlueprintRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BotBlueprintRepository::class)]
class BotBlueprint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private array $args = [];

    #[ORM\Column]
    private ?\DateInterval $frequency = null;

    public function getId(): ?int
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

    public function setArgs(array $args): static
    {
        $this->args = $args;

        return $this;
    }

    public function getArgs(): array
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
}
