<?php

declare(strict_types=1);

namespace App\Tests\Application\Auth;

use App\Application\Auth\UserRepository;
use App\Entity\User;
use Symfony\Component\Uid\Uuid;

final class MemoryUserRepository implements UserRepository
{
    /**
     * @var array<string, User>
     */
    private array $users = [];

    public function __construct(User ...$users)
    {
        foreach ($users as $user) {
            $this->addUser($user);
        }
    }

    private function addUser(User $user): void
    {
        $this->users += [$user->getId()->toString() => $user];
    }

    public function save(User $user): User
    {
        if (null != $user->getId()) {
            $this->users[$user->getId()->toString()] = $user;
        } else {
            $this->setUserId($user);
            $this->addUser($user);
        }

        return $user;
    }

    private function setUserId(User $user): void
    {
        $id = Uuid::v7();
        $reflector = new \ReflectionObject($user);
        $idProperty = $reflector->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($user, $id);
        $idProperty->setAccessible(false);
    }

    public function load(Uuid $id): ?User
    {
        if (array_key_exists($id->toString(), $this->users)) {
            return null;
        }

        return $this->users[$id->toString()];
    }
}
