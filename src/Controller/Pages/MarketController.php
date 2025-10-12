<?php

declare(strict_types=1);

namespace App\Controller\Pages;

use App\Application\Market\CreateOfferDto;
use App\Application\Market\MarketService;
use App\Application\Market\TraderDto;
use App\Domain\Market\TraderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
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
        $id = '0199D2E7-1CC5-7565-9302-5FD42AB77313';
        $trader = $this->traderRepo->find($id);
        $trader = TraderDto::fromEntity($trader);
        $offers = $this->marketService->listOffers();
        return $this->render('market/index.html.twig', [
            'trader' => $trader,
            'offers' => $offers
        ]);
    }

    #[Route('market/_buy/{offerId}', methods: 'POST')]
    public function buy(
        Uuid $offerId,
        Request $request
    ): Response {
        $traderId = $request->headers->get('X-Trader-Id');
        $traderId = Uuid::fromString($traderId);
        $updatedTrader = $this->marketService->buyOffer($traderId, $offerId);
        $response = $this->render('market/_trader.html.twig', [
            'trader' => $updatedTrader
        ]);
        $response->headers->set('HX-Trigger', 'refresh-offers');
        return $response;
    }

    #[Route('market/_sell', methods: 'POST')]
    public function sell(
        #[MapRequestPayload] CreateOfferDto $createOfferRequest,
        Request $request
    ): Response {
        $traderId = $request->headers->get('X-Trader-Id');
        $traderId = Uuid::fromString($traderId);
        $createdOffer = $this->marketService->createOffer($traderId, $createOfferRequest);
        return $this->render(
            'market/_offers.html.twig',
            [
                'offers' => $createdOffer->offers
            ]
        );
    }

    #[Route('market/_list', methods: 'GET')]
    public function list(): Response
    {
        $offers = $this->marketService->listOffers();
        return $this->render(
            'market/_offers.html.twig',
            [
                'offers' => $offers
            ]
        );
    }
}
