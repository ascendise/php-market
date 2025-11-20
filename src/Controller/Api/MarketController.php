<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Application\Market\CreateOfferDto;
use App\Application\Market\MarketService;
use App\Entity\Market\Trader;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

final class MarketController extends AbstractController
{
    public function __construct(
        private readonly MarketService $marketService,
    ) {
    }

    #[Route('api/market', methods: 'GET', format: 'json')]
    public function list(UserInterface $user): JsonResponse
    {
        $offers = $this->marketService->listOffers();

        return $this->json([
            'user' => $this->toTrader($user)->getId(),
            'offers' => $offers,
        ]);
    }

    private function toTrader(UserInterface $user): Trader
    {
        /** @var User $user * */
        $user = $user;

        return $user->getTrader();
    }

    #[Route('api/market/buy/{offerId}', methods: 'POST', format: 'json')]
    public function buy(
        Uuid $offerId,
        Request $request,
        UserInterface $user,
    ): JsonResponse {
        $trader = $this->toTrader($user);
        $updatedTrader = $this->marketService->buyOffer($trader->getId(), $offerId);

        return $this->json($updatedTrader);
    }

    #[Route('api/market/sell', methods: 'POST', format: 'json')]
    public function sell(
        #[MapRequestPayload] CreateOfferDto $createOfferRequest,
        Request $request,
        UserInterface $user,
    ): JsonResponse {
        $trader = $this->toTrader($user);
        $createdOffer = $this->marketService->createOffer($trader->getId(), $createOfferRequest);

        return $this->json($createdOffer);
    }
}
