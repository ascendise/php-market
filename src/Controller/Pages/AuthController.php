<?php

namespace App\Controller\Pages;

use App\Application\Auth\AuthenticationService;
use App\Application\Auth\RegistrationException;
use App\Application\Auth\UserCommandDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Mercure\Authorization;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class AuthController extends AbstractController
{
    public function __construct(private readonly AuthenticationService $authService)
    {
    }

    #[Route('auth/register')]
    public function register(): Response
    {
        return $this->render('auth/register.html.twig');
    }

    #[Route('auth/_register', methods: ['POST'])]
    #[IsCsrfTokenValid('register')]
    public function createAccount(#[MapRequestPayload] UserCommandDto $createUser): Response
    {
        try {
            $user = $this->authService->createUser($createUser);
            $response = new Response(headers: ['HX-Push-Url' => '/auth/login']);

            return $this->render('auth/login.html.twig', ['lastEmail' => $createUser->email], $response);
        } catch (RegistrationException $e) {
            return $this->render('auth/register.html.twig', ['error' => (string) $e]);
        }
    }

    #[Route('auth/login', methods: ['GET', 'POST'])]
    public function login(
        Request $request,
        AuthenticationUtils $authUtils,
        Authorization $authorization,
    ): Response {
        $error = $authUtils->getLastAuthenticationError();
        $lastEmail = $authUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'lastEmail' => $lastEmail,
            'error' => $error,
        ]);
    }
}
