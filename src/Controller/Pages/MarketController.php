<?php

declare(strict_types=1);

namespace App\Controller\Pages;

use App\Application\HAL\LinkPopulator;
use App\Application\Market\MarketDto;
use App\Application\Market\MarketService;
use App\Application\Market\OfferCommandDto;
use App\Application\Market\TraderDto;
use App\Application\RateLimit\RateLimitGuard;
use App\Entity\Market\Trader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Symfony\Component\Uid\Uuid;

final class MarketController extends AbstractController
{
    public function __construct(
        private readonly RateLimitGuard $rateLimiter,
        private readonly MarketService $marketService,
        private readonly LinkPopulator $linkPopulator,
    ) {
    }

    #[Route('/')]
    public function index(UserInterface $user): Response
    {
        return $this->rateLimiter->guard(function () use ($user) {
            $trader = $this->fetchTrader($user);
            $trader = TraderDto::fromEntity($trader->toEntity());
            $offers = $this->marketService->listOffers();
            $market = new MarketDto($offers);

            return $this->render('market/index.html.twig', [
                'trader' => $this->linkPopulator->populateWebLinks($trader),
                'market' => $this->linkPopulator->populateWebLinks($market),
            ]);
        }, $user->getUserIdentifier());
    }

    private function fetchTrader(UserInterface $user): Trader
    {
        /** @var \App\Entity\User */
        $user = $user;

        return $user->getTrader();
    }

    #[Route('market/_buy/{offerId}', methods: 'POST')]
    #[IsCsrfTokenValid('buy', tokenKey: 'X-CSRF-TOKEN', tokenSource: IsCsrfTokenValid::SOURCE_HEADER)]
    public function buy(
        Uuid $offerId,
        UserInterface $user,
    ): Response {
        return $this->rateLimiter->guard(function () use ($offerId, $user) {
            $traderId = $this->fetchTrader($user)->getId();
            $updatedTrader = $this->marketService->buyOffer($traderId, $offerId);
            $response = $this->render('market/_trader_inventory.html.twig', [
                'trader' => $this->linkPopulator->populateWebLinks($updatedTrader),
            ]);

            return $response;
        }, $user->getUserIdentifier());
    }

    #[Route('market/_sell', methods: 'POST')]
    #[IsCsrfTokenValid('sell')]
    public function sell(
        #[MapRequestPayload] OfferCommandDto $createOfferRequest,
        UserInterface $user,
    ): Response {
        return $this->rateLimiter->guard(function () use ($createOfferRequest, $user) {
            $trader = $this->fetchTrader($user);
            $createdOffer = $this->marketService->createOffer($trader->getId(), $createOfferRequest);
            $response = $this->render(
                'market/_trader_inventory.html.twig',
                [
                    'trader' => $this->linkPopulator->populateWebLinks($createdOffer->seller),
                ]
            );

            return $response;
        }, $user->getUserIdentifier());
    }

    #[Route('market/_trader/inventory', methods: 'GET')]
    public function traderInventory(UserInterface $user): Response
    {
        return $this->rateLimiter->guard(function () use ($user) {
            $trader = $this->fetchTrader($user);
            $trader = TraderDto::fromEntity($trader->toEntity());

            return $this->render(
                'market/_trader_inventory.html.twig',
                [
                    'trader' => $this->linkPopulator->populateWebLinks($trader),
                ]
            );
        }, $user->getUserIdentifier());
    }
}
