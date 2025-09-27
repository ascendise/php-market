<?php

declare(strict_types=1);

namespace App\Controller\Pages;

use App\Application\Market\InventoryDto;
use App\Application\Market\ItemDto;
use App\Application\Market\OfferDto;
use App\Application\Market\OffersDto;
use App\Application\Market\ProductDto;
use App\Application\Market\TraderDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MarketController extends AbstractController
{
    #[Route('market')]
    public function index(): Response
    {
        $inventory = new InventoryDto(
            new ItemDto(new ProductDto('Graphics Card'), 12),
            new ItemDto(new ProductDto('Ak-47'), 2),
            new ItemDto(new ProductDto('7.62x39 FMJ'), 900),
        );
        $trader = new TraderDto(1000, $inventory);
        $offers = new OffersDto(
            new OfferDto(new ProductDto('Bobblehead Figurine'), 1, 52000),
            new OfferDto(new ProductDto('Foam Isolation'), 10, 35),
            new OfferDto(new ProductDto('Makarov'), 3, 300),
            new OfferDto(new ProductDto('C.A.T. (Tourniquet)'), 10, 45),
        );
        return $this->render('market/index.html.twig', [
            'trader' => $trader,
            'offers' => $offers
        ]);
    }
}
