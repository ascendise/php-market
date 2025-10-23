<?php

declare(strict_types=1);

namespace App\Application\Auth;

use App\Application\Market\TraderDto;
use App\Entity\User;
use Symfony\Component\Uid\Uuid;

final class UserDto
{
    public function __construct(
        public readonly Uuid $id,
        public readonly string $email,
        public readonly TraderDto $trader,
    ) {
    }

    public static function fromEntity(User $user): UserDto
    {
        return new UserDto(
            $user->getId(),
            $user->getEmail(),
            TraderDto::fromEntity($user->getTrader()->toEntity())
        );
    }
}
