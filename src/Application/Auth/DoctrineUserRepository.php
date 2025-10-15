<?php

declare(strict_types=1);

namespace App\Application\Auth;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

final class DoctrineUserRepository implements UserRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function save(User $user): User
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function load(Uuid $id): ?User
    {
        $users = $this->entityManager->getRepository(User::class);

        return $users->find($id);
    }
}
