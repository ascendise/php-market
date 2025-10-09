<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Application\Market\MarketService;
use CreateOfferDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class MarketController extends AbstractController
{
    public function __construct(
        private readonly MarketService $marketService,
    ) {
    }

    #[Route('api/market/buy/{offerId}', methods: 'POST')]
    public function buy(
        Uuid $offerId,
        Request $request
    ): JsonResponse {
        $traderId = $request->headers->get('X-Trader-Id');
        $traderId = Uuid::fromString($traderId);
        $updatedTrader = $this->marketService->buyOffer($offerId, $traderId);
        return $this->json($updatedTrader);
    }

    #[Route('api/market/sell', methods: 'POST')]
    public function sell(
        CreateOfferDto $createOfferRequest,
        Request $request
    ): JsonResponse {
        $traderId = $request->headers->get('X-Trader-Id');
        $traderId = Uuid::fromString($traderId);
        $createdOffer = $this->marketService->createOffer($traderId, $createOfferRequest);
        return $this->json($createdOffer);
    }
}
