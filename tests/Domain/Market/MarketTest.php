<?php

declare(strict_types=1);

namespace App\Tests\Domain\Market;

use App\Domain\Market\Offer;
use App\Domain\Market\Offers;
use App\Domain\Market\Market;
use App\Domain\Market\Product;
use PHPUnit\Framework\TestCase;

class MarketTest extends TestCase
{
    private static function setupSut(Offers $initOffers): Market
    {
        $offerRepo = new MemoryOfferRepository($initOffers);
        return new Market($offerRepo);
    }

    public function testListOffersShouldReturnAllOffersInRepository(): void
    {
        // Arrange
        $seller = new StubTrader();
        $expectedOffers = new Offers(
            new Offer(product: new Product('Ben and Jerrys'), price: 8, quantity: 1, seller: $seller),
            new Offer(product: new Product('Tires'), price: 200, quantity: 4, seller: $seller)
        );
        $sut = MarketTest::setupSut($expectedOffers);
        // Act
        $actualOffers = $sut->listOffers();
        // Assert
        $this->assertEquals($expectedOffers, $actualOffers);
    }
}
