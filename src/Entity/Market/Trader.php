<?php

namespace App\Entity\Market;

use App\Domain;
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

    /**
     * @var Collection<int, Offer>
     */
    #[ORM\OneToMany(targetEntity: Offer::class, mappedBy: 'seller', orphanRemoval: true)]
    private Collection $offers;

    public function __construct()
    {
        $this->inventory = new ArrayCollection();
        $this->offers = new ArrayCollection();
    }

    public function fromEntity(Domain\Market\Trader $entity): Trader
    {
        $trader = new Trader();
        $trader->id = $entity->id();
        foreach ($entity->listInventory() as $item) {
            $trader->inventory->add(Item::fromEntity($item), $entity);
        }
        $trader->setBalance($entity->balance());
    }

    public function toEntity(): Domain\Market\Trader
    {
        $inventory = new Domain\Market\Inventory();
        foreach ($this->inventory as $item) {
            $inventory->add($item->toEntity());
        }
        $balance = new Domain\Market\Balance($this->balance);
        return new Domain\Market\Trader($this->id, $inventory, $balance);
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

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): static
    {
        if (!$this->offers->contains($offer)) {
            $this->offers->add($offer);
            $offer->setSeller($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): static
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getSeller() === $this) {
                $offer->setSeller(null);
            }
        }

        return $this;
    }
}
