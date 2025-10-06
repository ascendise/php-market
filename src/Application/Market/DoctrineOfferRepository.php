<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\CreateOffer;
use App\Domain\Market\Offer;
use App\Domain\Market\OfferRepository;
use App\Domain\Market\Offers;
use App\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class DoctrineOfferRepository implements OfferRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function list(): Offers
    {
        $offers = $this->offerRepo()->findAll();
        return new Offers(...array_map(fn (Entity\Market\Offer $o) => $o->toEntity(), $offers));
    }
    /**
     * @return EntityRepository<Entity\Market\Offer>
     */
    private function offerRepo(): EntityRepository
    {
        return $this->entityManager->getRepository(Entity\Market\Offer::class);
    }

    public function create(CreateOffer $offer): Offer
    {
        $offerModel = Entity\Market\Offer::fromEntity($offer);
        $this->entityManager->persist($offerModel);
        $this->entityManager->flush();
        return $offerModel->toEntity();
    }

    public function findById(string $id): ?Offer
    {
        $offer = $this->offerRepo()->find($id);
        if (!$offer || !$offer instanceof Entity\Market\Offer) {
            return null;
        }
        return new $offer->toEntity();
    }

    public function remove(string $id): void
    {
        $offer = $this->findById($id);
        if (!$offer) {
            return;
        }
        $this->entityManager->remove($offer);
        $this->entityManager->persist();
    }
}
