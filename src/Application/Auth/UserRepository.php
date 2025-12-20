<?php

declare(strict_types=1);

namespace App\Application\Auth;

use App\Application\Market\TraderDto;
use App\Entity\User;

interface UserRepository
{
    public function save(User $user): User;

    public function fetchByEmail(string $email): ?User;

    public function fetchFromTrader(TraderDto $trader): User;
}
