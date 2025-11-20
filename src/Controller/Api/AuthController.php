<?php

namespace App\Controller\Api;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class AuthController extends AbstractController
{
    public function __construct(private readonly JWTTokenManagerInterface $jwtManager)
    {
    }

    #[Route('/api/auth/login', methods: ['POST'], format: 'json')]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }
        $token = $this->jwtManager->create($user);

        return $this->json([
            'user' => $user->getUserIdentifier(),
            'token' => $token,
        ]);
    }
}
