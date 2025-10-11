<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Application\Market\CreateOfferDto;
use App\Application\Market\MarketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class MarketController extends AbstractController
{
    public function __construct(
        private readonly MarketService $marketService,
    ) {
    }

    #[Route('api/market/buy/{offerId}', methods: 'POST', format: 'json')]
    public function buy(
        Uuid $offerId,
        Request $request
    ): JsonResponse {
        $traderId = $request->headers->get('X-Trader-Id');
        $traderId = Uuid::fromString($traderId);
        $updatedTrader = $this->marketService->buyOffer($traderId, $offerId);
        return $this->json($updatedTrader);
    }

    #[Route('api/market/sell', methods: 'POST', format: 'json')]
    public function sell(
        #[MapRequestPayload] CreateOfferDto $createOfferRequest,
        Request $request
    ): JsonResponse {
        $traderId = $request->headers->get('X-Trader-Id');
        $traderId = Uuid::fromString($traderId);
        $createdOffer = $this->marketService->createOffer($traderId, $createOfferRequest);
        return $this->json($createdOffer);
    }
}
