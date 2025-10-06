<?php

declare(strict_types=1);

namespace App\Controller\Pages;

use App\Application\Market\InventoryDto;
use App\Application\Market\MarketService;
use App\Application\Market\TraderDto;
use App\Domain\Market\TraderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

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
        $id = '0199bb8a-140b-7a25-a024-45cefa1fffba';
        //$trader = $this->traderRepo->find($id);
        $trader = new TraderDto(Uuid::v7(), 1000, new InventoryDto());
        $offers = $this->marketService->listOffers();
        return $this->render('market/index.html.twig', [
            'trader' => $trader,
            'offers' => $offers
        ]);
    }
}
