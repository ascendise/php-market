<?php

declare(strict_types=1);

namespace App\Controller\Pages;

use App\Application\Market\MarketService;
use App\Application\Market\TraderDto;
use App\Domain\Market\TraderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MarketController extends AbstractController
{
    public function __construct(
        private readonly MarketService $marketService,
        private readonly TraderRepository $traderRepo
    ) {
    }

    #[Route('market')]
    public function index(): Response
    {
        $id = '0199D2E7-1CC5-7565-9302-5FD42AB77313';
        $trader = $this->traderRepo->find($id);
        $trader = TraderDto::fromEntity($trader);
        $offers = $this->marketService->listOffers();
        return $this->render('market/index.html.twig', [
            'trader' => $trader,
            'offers' => $offers
        ]);
    }
}
