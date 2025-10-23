<?php

declare(strict_types=1);

namespace App\Tests\Application\Auth;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * A fake UserPasswordHasher for unit-tests that does not actually generate a hash
 * but merely reverses the password.
 */
final class FakeUserPasswordHasher implements UserPasswordHasherInterface
{
    public function hashPassword(PasswordAuthenticatedUserInterface $user, string $plainPassword): string
    {
        return strrev($plainPassword);
    }

    public function isPasswordValid(PasswordAuthenticatedUserInterface $user, string $plainPassword): bool
    {
        return $user->getPassword() == $this->hashPassword($user, $plainPassword);
    }

    public function needsRehash(PasswordAuthenticatedUserInterface $user): bool
    {
        return false;
    }

    public static function assertIsHashed(string $hashedPassword, string $plainPassword): bool
    {
        return strrev($hashedPassword) == $plainPassword;
    }
}
