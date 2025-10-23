<?php

declare(strict_types=1);

namespace App\Application\Auth;

use App\Entity\User;

interface UserRepository
{
    public function save(User $user): User;

    public function load(string $email): ?User;
}
