<?php

declare(strict_types=1);

namespace App\Tests\Application\Auth;

use App\Application\Auth\UserRepository;
use App\Application\Market\TraderDto;
use App\Entity\User;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

final class MemoryUserRepository implements UserRepository
{
    /**
     * @var array<string, User>
     */
    private array $users = [];

    /**
     * @var \Iterator<UuidV7>
     */
    private \Iterator $uuidGen;

    /**
     * @param ?\Iterator<UuidV7> $uuidGen
     */
    public function __construct(?\Iterator $uuidGen, User ...$users)
    {
        foreach ($users as $user) {
            $this->addUser($user);
        }
        $this->uuidGen = $uuidGen ?? MemoryUserRepository::randomGen();
    }

    /**
     * @return \Iterator<UuidV7>
     */
    private static function randomGen(): \Iterator
    {
        /* @phpstan-ignore while.alwaysTrue */
        while (true) {
            yield Uuid::v7();
        }
    }

    private function getNextId(): UuidV7
    {
        $id = $this->uuidGen->current();
        $this->uuidGen->next();
        if (null == $id) {
            $this->uuidGen->rewind();
            $id = $this->uuidGen->current();
        }

        return $id;
    }

    private function addUser(User $user): void
    {
        $this->users += [$user->getEmail() => $user];
    }

    public function save(User $user): User
    {
        if (null != $user->getId()) {
            $this->users[$user->getEmail()] = $user;
        } else {
            $this->setId($user);
            $this->addUser($user);
            $this->setId($user->getTrader());
        }

        return $user;
    }

    private function setId(mixed $object): void
    {
        $id = $this->getNextId();
        $reflector = new \ReflectionObject($object);
        $idProperty = $reflector->getProperty('id');
        $idProperty->setValue($object, $id);
    }

    public function fetchByEmail(string $email): ?User
    {
        if (!array_key_exists($email, $this->users)) {
            return null;
        }

        return $this->users[$email];
    }

    /**
     * @return array<string, User>
     */
    public function list(): array
    {
        return $this->users;
    }

    public function fetchFromTrader(TraderDto $trader): User
    {
        foreach ($this->users as $user) {
            if ($user->getTrader()->getId() == $trader->id) {
                return $user;
            }
        }
        throw new \InvalidArgumentException('Orphaned trader!');
    }
}
