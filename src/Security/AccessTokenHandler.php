<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

final class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        throw new \Exception('getUserBadgeFrom');
    }
}
