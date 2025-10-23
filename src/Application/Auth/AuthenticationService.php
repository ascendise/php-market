<?php

declare(strict_types=1);

namespace App\Application\Auth;

interface AuthenticationService
{
    /**
     * @exception RegistrationException
     */
    public function createUser(CreateUserDto $createUser): UserDto;

    public function login(LoginDto $login): ?UserDto;
}
