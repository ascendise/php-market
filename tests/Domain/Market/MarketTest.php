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
    public function testListOffersShouldReturnAllOffersInRepository(): void
    {
        // Arrange
        $seller = new StubTrader();
        $expectedOffers = new Offers(
            new Offer(product: new Product('Ben and Jerrys'), price: 8, quantity: 1, seller: $seller),
            new Offer(product: new Product('Tires'), price: 200, quantity: 4, seller: $seller)
        );
        $offerRepo = new MemoryOfferRepository($expectedOffers);
        $sut = new Market($offerRepo);
        // Act
        $actualOffers = $sut->listOffers();
        // Assert
        $this->assertEquals($expectedOffers, $actualOffers);
    }

    public function testCreateOfferShouldAddOfferToRepository(): void
    {
        // Arrange
        $offerRepo = new MemoryOfferRepository(new Offers());
        $sut = new Market($offerRepo);
        // Act
        $seller = new StubTrader();
        $new_offer = new Offer(product: new Product('Ben and Jerrys'), price: 8, quantity: 1, seller: $seller);
        $sut->createOffer($new_offer);
        // Assert
        $offers = $offerRepo->list();
        $this->assertContains($new_offer, $offers);
    }
}
