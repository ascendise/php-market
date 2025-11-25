<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Application\HAL\LinkPopulator;
use App\Application\Market\CreateOfferDto;
use App\Application\Market\MarketDto;
use App\Application\Market\MarketService;
use App\Application\RateLimit\RateLimitGuard;
use App\Entity\Market\Trader;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

final class MarketController extends AbstractController
{
    public function __construct(
        private readonly RateLimitGuard $rateLimiter,
        private readonly MarketService $marketService,
        private readonly LinkPopulator $linkPopulator,
    ) {
    }

    #[Route('api/market', methods: 'GET', format: 'json')]
    public function list(UserInterface $user): Response
    {
        return $this->rateLimiter->guard(function () {
            $offers = $this->marketService->listOffers();
            $market = new MarketDto($offers);
            $this->linkPopulator->populateRestLinks($market);

            return $this->json($market, headers: ['Content-Type' => 'application/hal+json']);
        }, $user->getUserIdentifier());
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
        UserInterface $user,
    ): Response {
        return $this->rateLimiter->guard(function () use ($offerId, $user) {
            $trader = $this->toTrader($user);
            $updatedTrader = $this->marketService->buyOffer($trader->getId(), $offerId);
            $this->linkPopulator->populateRestLinks($updatedTrader);

            return $this->json($updatedTrader, headers: ['Content-Type' => 'application/hal+json']);
        }, $user->getUserIdentifier());
    }

    #[Route('api/market/sell', methods: 'POST', format: 'json')]
    public function sell(
        #[MapRequestPayload] CreateOfferDto $createOfferRequest,
        UserInterface $user,
    ): Response {
        return $this->rateLimiter->guard(function () use ($createOfferRequest, $user) {
            $trader = $this->toTrader($user);
            $createdOffer = $this->marketService->createOffer($trader->getId(), $createOfferRequest);
            $this->linkPopulator->populateRestLinks($createdOffer);

            return $this->json($createdOffer, headers: ['Content-Type' => 'application/hal+json']);
        }, $user->getUserIdentifier());
    }
}
