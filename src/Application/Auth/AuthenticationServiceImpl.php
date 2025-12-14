<?php

declare(strict_types=1);

namespace App\Application\Auth;

use App\Application\Market\TraderDto;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthenticationServiceImpl implements AuthenticationService
{
    public function __construct(
        private readonly UserRepository $userRepo,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly TraderInitializer $traderInitializer,
    ) {
    }

    public function createUser(UserCommandDto $createUser): UserDto
    {
        $userExists = null != $this->userRepo->load($createUser->email);
        if ($userExists) {
            throw new RegistrationException(RegistrationError::UserAlreadyExists);
        }
        $user = new User();
        $user->setEmail($createUser->email);
        $hashedPassword = $this->hasher->hashPassword($user, $createUser->password);
        $user->setPassword($hashedPassword);
        $trader = $this->traderInitializer->init();
        $user->setTrader($trader);
        $this->userRepo->save($user);

        return new UserDto(
            $user->getId(),
            $user->getEmail(),
            TraderDto::fromEntity($trader->toEntity()),
        );
    }

    public function login(LoginDto $login): ?UserDto
    {
        $user = $this->userRepo->load($login->email);
        if (null == $user) {
            return null;
        }
        $hasValidCredentials = $this->hasher->isPasswordValid($user, $login->password);
        if (!$hasValidCredentials) {
            return null;
        }

        return UserDto::fromEntity($user);
    }
}
