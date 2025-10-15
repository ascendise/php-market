<?php

declare(strict_types=1);

namespace App\Application\Auth;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthenticationServiceImpl implements AuthenticationService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly UserRepository $userRepo,
    ) {
    }

    public function createUser(CreateUserDto $createUser): UserDto
    {
        throw new \Exception('AuthenticationServiceImpl.createUser() not implemented');
    }

    public function login(LoginDto $login): UserDto
    {
        throw new \Exception('AuthenticationServiceImpl.login() not implemented');
    }
}
