<?php

declare(strict_types=1);

namespace App\Application\Auth;

enum RegistrationError: int
{
    case UserAlreadyExists = 1;
}
