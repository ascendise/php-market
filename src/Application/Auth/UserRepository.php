<?php

declare(strict_types=1);

namespace App\Application\Auth;

use App\Entity\User;
use Symfony\Component\Uid\Uuid;

interface UserRepository
{
    public function save(User $user): User;

    public function load(Uuid $id): ?User;
}
