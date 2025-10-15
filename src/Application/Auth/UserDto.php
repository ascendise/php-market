<?php

declare(strict_types=1);

namespace App\Application\Auth;

use App\Application\Market\TraderDto;

final class UserDto
{
    public function __construct(
        public readonly string $email,
        public readonly TraderDto $trader,
    ) {
    }
}
