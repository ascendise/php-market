<?php

declare(strict_types=1);

namespace App\Controller\Pages;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MarketController extends AbstractController
{
    #[Route('market')]
    public function index(): Response
    {
        return $this->render('market/index.html.twig', [
            'trader' => [],
            'market' => []
        ]);
    }
}
