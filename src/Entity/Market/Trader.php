<?php

namespace App\Entity\Market;

use App\Repository\Market\TraderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TraderRepository::class)]
class Trader
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column]
    private ?int $balance = null;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $inventory;

    public function __construct()
    {
        $this->inventory = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getBalance(): ?int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getInventory(): Collection
    {
        return $this->inventory;
    }

    public function addInventory(Item $inventory): static
    {
        if (!$this->inventory->contains($inventory)) {
            $this->inventory->add($inventory);
            $inventory->setOwner($this);
        }

        return $this;
    }

    public function removeInventory(Item $inventory): static
    {
        if ($this->inventory->removeElement($inventory)) {
            // set the owning side to null (unless already changed)
            if ($inventory->getOwner() === $this) {
                $inventory->setOwner(null);
            }
        }

        return $this;
    }
}
