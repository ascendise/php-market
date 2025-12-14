<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\Offer;
use App\Domain\Market\OfferCommand;
use App\Domain\Market\OfferRepository;
use App\Domain\Market\Offers;
use App\Domain\Market\Seller;
use App\Domain\Market\TraderRepository;
use App\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class DoctrineOfferRepository implements OfferRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TraderRepository $traderRepo,
    ) {
    }

    public function list(): Offers
    {
        $offers = $this->offerRepo()->findAll();
        $traders = $this->getSellersFromOffers($offers);
        $offers = array_map(fn (Entity\Market\Offer $o) => $o->toEntity($traders[$o->getSellerId()]), $offers);
        $offers = new Offers(...$offers);

        return $offers;
    }

    /**
     * @param array<int, Entity\Market\Offer> $offers
     *
     * @return array<string, Seller>
     */
    private function getSellersFromOffers(array $offers): array
    {
        $sellerIds = array_map(fn (Entity\Market\Offer $o) => $o->getSellerId(), $offers);
        $indexedSellers = [];
        foreach ($sellerIds as $sellerId) {
            $seller = $this->traderRepo->find($sellerId);
            $indexedSellers += [$seller->id() => $seller];
        }

        return $indexedSellers;
    }

    /**
     * @return EntityRepository<Entity\Market\Offer>
     */
    private function offerRepo(): EntityRepository
    {
        return $this->entityManager->getRepository(Entity\Market\Offer::class);
    }

    public function findById(string $id): ?Offer
    {
        $offer = $this->offerRepo()->find($id);
        if (!$offer) {
            return null;
        }
        $seller = $this->traderRepo->find($offer->getSellerId());

        return $offer->toEntity($seller);
    }

    public function remove(string $id): void
    {
        $offer = $this->offerRepo()->find($id);
        if (!$offer) {
            return;
        }
        $this->entityManager->remove($offer);
        $this->entityManager->flush();
    }

    public function create(OfferCommand $offer): Offer
    {
        $seller = $this->traderRepo->find($offer->seller()->id());
        $offerModel = Entity\Market\Offer::fromEntity($offer, $seller);
        $this->entityManager->persist($offerModel);
        $this->entityManager->flush();

        return $offerModel->toEntity($seller);
    }
}
