<?php

declare(strict_types=1);

namespace App\Controller\Pages;

use App\Application\Market\CreateOfferDto;
use App\Application\Market\MarketService;
use App\Application\Market\TraderDto;
use App\Domain\Market\TraderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class MarketController extends AbstractController
{
    private string $id = '0199D2E7-1CC5-7565-9302-5FD42AB77313';

    public function __construct(
        private readonly MarketService $marketService,
        private readonly TraderRepository $traderRepo,
    ) {
    }

    #[Route('market')]
    public function index(): Response
    {
        $trader = $this->traderRepo->find($this->id);
        $trader = TraderDto::fromEntity($trader);
        $offers = $this->marketService->listOffers();

        return $this->render('market/index.html.twig', [
            'trader' => $trader,
            'offers' => $offers,
        ]);
    }

    #[Route('market/_buy/{offerId}', methods: 'POST')]
    public function buy(
        Uuid $offerId,
        Request $request,
    ): Response {
        $traderId = $request->headers->get('X-Trader-Id');
        $traderId = Uuid::fromString($traderId);
        $updatedTrader = $this->marketService->buyOffer($traderId, $offerId);
        $response = $this->render('market/_trader.html.twig', [
            'trader' => $updatedTrader,
        ]);
        $response->headers->set('HX-Trigger', 'offers-update');

        return $response;
    }

    #[Route('market/_sell', methods: 'POST')]
    public function sell(
        #[MapRequestPayload] CreateOfferDto $createOfferRequest,
        Request $request,
    ): Response {
        $traderId = $request->headers->get('X-Trader-Id');
        $traderId = Uuid::fromString($traderId);
        $createdOffer = $this->marketService->createOffer($traderId, $createOfferRequest);
        $response = $this->render(
            'market/_offers.html.twig',
            [
                'offers' => $createdOffer->offers,
            ]
        );
        $response->headers->set('HX-Trigger', 'trader-update');

        return $response;
    }

    #[Route('market/_offers', methods: 'GET')]
    public function offers(): Response
    {
        $offers = $this->marketService->listOffers();

        return $this->render(
            'market/_offers.html.twig',
            [
                'offers' => $offers,
            ]
        );
    }

    #[Route('market/_trader', methods: 'GET')]
    public function trader(): Response
    {
        $trader = $this->traderRepo->find($this->id);
        $trader = TraderDto::fromEntity($trader);

        return $this->render(
            'market/_trader.html.twig',
            [
                'trader' => $trader,
            ]
        );
    }
}
