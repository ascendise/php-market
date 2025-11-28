<?php

namespace App\Entity\Market;

use App\Domain;
use App\Domain\Market\Seller;
use App\Repository\OfferRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV7;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
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

    #[ORM\Column]
    private ?int $totalPrice = null;

    #[ORM\Column(length: 127)]
    private ?string $sellerId = null;

    public static function fromEntity(Domain\Market\OfferCommand $entity, Seller $seller): Offer
    {
        $offer = new Offer();
        $offer->setProductName($entity->product()->name);
        $offer->setQuantity($entity->quantity());
        $offer->setTotalPrice($entity->totalPrice());
        $offer->setSellerId($seller->id());

        return $offer;
    }

    public function toEntity(Seller $seller): Domain\Market\Offer
    {
        $product = new Domain\Market\Product($this->getProductName());
        $quantity = $this->getQuantity();
        $pricePerItem = intdiv($this->getTotalPrice(), $quantity);

        return new Domain\Market\Offer(
            $this->getId()->toString(),
            $product,
            $pricePerItem,
            $quantity,
            $seller
        );
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

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getSellerId(): ?string
    {
        return $this->sellerId;
    }

    public function setSellerId(?string $sellerId): static
    {
        $this->sellerId = $sellerId;

        return $this;
    }
}
