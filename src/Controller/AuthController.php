<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{
    #[Route('auth/register')]
    public function register(): Response
    {
        return $this->render('auth/register.html.twig', []);
    }

    #[Route('auth/_register')]
    public function createAccount(): Response
    {
        throw new \Exception('Not implemented');
    }

    #[Route('auth/login')]
    public function login(): Response
    {
        return $this->render('auth/login.html.twig', []);
    }

    #[Route('auth/_login')]
    public function loadAccount(): Response
    {
        throw new \Exception('Not implemented');
    }
}
