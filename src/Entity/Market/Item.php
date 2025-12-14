<?php

namespace App\Entity\Market;

use App\Domain;
use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV7;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    // @phpstan-ignore property.unusedType
    private ?UuidV7 $id = null;

    #[ORM\Column(length: 255)]
    private ?string $productName = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'inventory')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trader $owner = null;

    public static function fromEntity(Domain\Market\Item $entity, Trader $owner): Item
    {
        $item = new Item();
        $item->setProductName($entity->product()->name);
        $item->setQuantity($entity->quantity());
        $item->setOwner($owner);

        return $item;
    }

    public function toEntity(): Domain\Market\Item
    {
        $product = new Domain\Market\Product($this->productName);

        return new Domain\Market\Item($product, $this->quantity);
    }

    public function getId(): ?UuidV7
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): static
    {
        $this->productName = $productName;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOwner(): ?Trader
    {
        return $this->owner;
    }

    public function setOwner(?Trader $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
