<?php

declare(strict_types=1);

namespace App\Tests\Application\Market;

use App\Application\Market\MarketServiceImpl;
use App\Application\Market\OfferDto;
use App\Application\Market\OffersDto;
use App\Application\Market\ProductDto;
use App\Domain\Market\Offer;
use App\Domain\Market\Offers;
use App\Domain\Market\Product;
use App\Tests\Domain\Market\MemoryOfferRepository;
use App\Tests\Domain\Market\StubTrader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class MarketServiceTest extends TestCase
{
    private function setupSut(Offers $initOffers): MarketServiceImpl
    {
        $offersRepository = new MemoryOfferRepository($initOffers);
        return new MarketServiceImpl($offersRepository);
    }

    public function testListOffersShouldReturnAllOffers(): void
    {
        // Arrange
        $seller = new StubTrader();
        $storedOffers = new Offers(
            new Offer(new Product("Apple"), pricePerItem: 2, quantity: 5, seller: $seller)
        );
        $sut = $this->setupSut($storedOffers);
        // Act
        $offers = $sut->listOffers();
        // Assert
        $expectedOffers = new OffersDto(
            new OfferDto(
                new ProductDto('Apple'),
                quantity: 5,
                totalPrice: 10,
                sellerId: Uuid::fromString($seller->id())
            )
        );
        $this->assertEquals($expectedOffers, $offers);
    }
}
