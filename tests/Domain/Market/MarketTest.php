<?php

declare(strict_types=1);

namespace App\Tests\Domain\Market;

use App\Domain\Market\Balance;
use App\Domain\Market\CreateOffer;
use App\Domain\Market\Inventory;
use App\Domain\Market\Item;
use App\Domain\Market\Offer;
use App\Domain\Market\Offers;
use App\Domain\Market\Market;
use App\Domain\Market\Product;
use App\Domain\Market\Trader;
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

    public function testTransferShouldMoveItemsAndMoney(): void
    {
        // Arrange
        $seller = new Trader('seller', new Inventory(), new Balance(0));
        $buyer = new Trader('buyer', new Inventory(), new Balance(1000));
        $offer = new Offer('offer', new Product('Graphics Card'), 100, 3, $seller);
        $offerRepo = new MemoryOfferRepository(new Offers($offer));
        $sut = new Market($offerRepo);
        // Act
        $sut->transact($buyer, $offer);
        // Assert
        $boughtItem = new Item(new Product('Graphics Card'), 3);
        $this->assertContainsEquals($boughtItem, $buyer->listInventory(), 'Buyer did not receive item!');
        $this->assertEquals($buyer->balance(), 700, 'Buyer did not transfer money!');
        $this->assertEquals($seller->balance(), 300, 'Selller did not receive money!');
        $this->assertEquals(new Offers(), $offerRepo->list(), 'Offer was not removed after transaction!');
    }
}
