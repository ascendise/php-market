<?php

namespace App\Entity\Market;

use App\Domain;
use App\Repository\OfferRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $productName = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?int $totalPrice = null;

    #[ORM\ManyToOne(inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trader $seller = null;

    public static function fromEntity(Domain\Market\Offer $entity): Offer
    {
        $offer = new Offer();
        $offer->setProductName($entity->product()->name());
        $offer->setQuantity($entity->quantity());
        $offer->setTotalPrice($entity->totalPrice());
        $seller = Trader::fromEntity($entity->seller());
        $offer->setSeller($entity->totalPrice());
    }

    public function toEntity(): Domain\Market\Offer
    {
        $product = new Domain\Market\Product($this->productName);
        $quantity = $this->quantity;
        $pricePerItem = $this->totalPrice / $quantity;
        $seller = $this->seller->toEntity();
        return new Domain\Market\Offer(
            $product,
            $pricePerItem,
            $quantity,
            $seller
        );
    }

    public function getId(): ?int
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

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getSeller(): ?Trader
    {
        return $this->seller;
    }

    public function setSeller(?Trader $seller): static
    {
        $this->seller = $seller;

        return $this;
    }
}
