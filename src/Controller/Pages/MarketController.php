<?php

declare(strict_types=1);

namespace App\Controller\Pages;

use App\Application\Market\CreateOfferDto;
use App\Application\Market\MarketService;
use App\Application\Market\TraderDto;
use App\Entity\Market\Trader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Symfony\Component\Uid\Uuid;

final class MarketController extends AbstractController
{
    public function __construct(
        private readonly MarketService $marketService,
    ) {
    }

    #[Route('/')]
    public function index(Security $security): Response
    {
        $trader = $this->fetchTrader($security);
        $trader = TraderDto::fromEntity($trader->toEntity());
        $offers = $this->marketService->listOffers();

        return $this->render('market/index.html.twig', [
            'trader' => $trader,
            'offers' => $offers,
        ]);
    }

    private function fetchTrader(Security $security): Trader
    {
        /** @var \App\Entity\User */
        $user = $security->getUser();

        return $user->getTrader();
    }

    #[Route('market/_buy/{offerId}', methods: 'POST')]
    #[IsCsrfTokenValid('buy')]
    public function buy(
        Uuid $offerId,
        Request $request,
        Security $security,
    ): Response {
        $traderId = $this->fetchTrader($security)->getId();
        $updatedTrader = $this->marketService->buyOffer($traderId, $offerId);
        $response = $this->render('market/_trader.html.twig', [
            'trader' => $updatedTrader,
        ]);
        $response->headers->set('HX-Trigger', 'offers-update');

        return $response;
    }

    #[Route('market/_sell', methods: 'POST')]
    #[IsCsrfTokenValid('sell')]
    public function sell(
        #[MapRequestPayload] CreateOfferDto $createOfferRequest,
        Request $request,
        Security $security,
    ): Response {
        $traderId = $this->fetchTrader($security)->getId();
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
    public function trader(Security $security): Response
    {
        $trader = $this->fetchTrader($security);
        $trader = TraderDto::fromEntity($trader->toEntity());

        return $this->render(
            'market/_trader.html.twig',
            [
                'trader' => $trader,
            ]
        );
    }
}
