<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Domain\Market\Offer;
use App\Domain\Market\OfferRepository;
use App\Domain\Market\Offers;
use App\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

final class DoctrineOfferRepository implements OfferRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function list(): Offers
    {
        $offers = $this->entityManager->getRepository(Entity\Market\Offer::class)->findAll();
        return new Offers(array_map(fn ($o) => $o->toEntity(), $offers));
    }

    public function add(Offer $offer): void
    {
        throw new Exception("DoctrineOfferRepository.list() not implemented");
    }
}
