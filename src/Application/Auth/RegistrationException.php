<?php

declare(strict_types=1);

namespace App\Application\Auth;

final class RegistrationException extends \Exception
{
    public function __construct(private readonly RegistrationError $error)
    {
        $this->code = $error->value;
    }

    public function error(): RegistrationError
    {
        return $this->error;
    }

    public function __toString(): string
    {
        return match ($this->error) {
            RegistrationError::UserAlreadyExists => 'User with this email already exists!',
        };
    }
}
