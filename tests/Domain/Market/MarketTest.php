<?php

declare(strict_types=1);

namespace App\Tests\Domain\Market;

use App\Domain\Market\CreateOffer;
use App\Domain\Market\Offer;
use App\Domain\Market\Offers;
use App\Domain\Market\Market;
use App\Domain\Market\Product;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class MarketTest extends TestCase
{
    public function testListOffersShouldReturnAllOffersInRepository(): void
    {
        // Arrange
        $seller = new StubTrader();
        $expectedOffers = new Offers(
            new Offer(
                Uuid::v7()->toString(),
                product: new Product('Ben and Jerrys'),
                pricePerItem: 8,
                quantity: 1,
                seller: $seller
            ),
            new Offer(
                Uuid::v7()->toString(),
                product: new Product('Tires'),
                pricePerItem: 200,
                quantity: 4,
                seller: $seller
            )
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
        $newOffer = new CreateOffer(
            product: new Product('Ben and Jerrys'),
            pricePerItem: 8,
            quantity: 1,
            seller: $seller
        );
        $sut->createOffer($newOffer);
        // Assert
        $offers = $offerRepo->list();
        $this->assertCount(1, $offers);
    }
}
