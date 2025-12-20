<?php

declare(strict_types=1);

namespace App\Tests\Domain\Market;

use App\Domain\Events\EventDispatcher;
use App\Domain\Market\Balance;
use App\Domain\Market\BalanceChangedEvent;
use App\Domain\Market\Inventory;
use App\Domain\Market\Item;
use App\Domain\Market\Market;
use App\Domain\Market\Offer;
use App\Domain\Market\OfferCreatedEvent;
use App\Domain\Market\OfferRepository;
use App\Domain\Market\Offers;
use App\Domain\Market\OfferSoldEvent;
use App\Domain\Market\Product;
use App\Domain\Market\Trader;
use App\Domain\Market\TraderRepository;
use App\Tests\Domain\Events\SpyEventDispatcher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class MarketTest extends TestCase
{
    private function setupSut(
        ?OfferRepository $offerRepo = null,
        ?TraderRepository $traderRepo = null,
        ?EventDispatcher $eventDispatcher = null,
    ): Market {
        $offerRepo ??= new MemoryOfferRepository(new Offers());
        $traderRepo ??= new MemoryTraderRepository();
        $eventDispatcher ??= new SpyEventDispatcher();

        return new Market($offerRepo, $traderRepo, $eventDispatcher);
    }

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
        $sut = $this->setupSut(new MemoryOfferRepository($expectedOffers));
        // Act
        $actualOffers = $sut->listOffers();
        // Assert
        $this->assertEquals($expectedOffers, $actualOffers);
    }

    public function testFindTraderShouldReturnTraderIfExists(): void
    {
        // Arrange
        $seller = new Trader(Uuid::v7()->toString(), new Inventory(), new Balance(1));
        $sut = $this->setupSut(traderRepo: new MemoryTraderRepository($seller));
        // Act
        $foundSeller = $sut->findTrader($seller->id());
        // Assert
        $this->assertEquals($seller, $foundSeller);
    }

    public function testFindTraderShouldReturnNullIfNotExists(): void
    {
        // Arrange
        $sut = $this->setupSut();
        // Act
        $foundSeller = $sut->findTrader('noid');
        // Assert
        $this->assertNull($foundSeller);
    }

    public function testCreateOfferShouldAddOfferToRepository(): void
    {
        // Arrange
        $offerRepo = new MemoryOfferRepository(new Offers());
        $seller = new Trader(
            Uuid::v7()->toString(),
            new Inventory(new Item(new Product('Ben and Jerrys'), 1)),
            new Balance(0)
        );
        $traderRepo = new MemoryTraderRepository($seller);
        $spyEventDispatcher = new SpyEventDispatcher();
        $sut = $this->setupSut($offerRepo, $traderRepo, $spyEventDispatcher);
        // Act
        $newOffer = $seller->sell(new Product('Ben and Jerrys'), price: 8, quantity: 1);
        $sut->createOffer($newOffer);
        // Assert
        $offers = $offerRepo->list();
        $this->assertCount(1, $offers);
        $dbSeller = $traderRepo->find($seller->id());
        $this->assertEquals($seller, $dbSeller, 'Trader not updated in DB!');
        $spyEventDispatcher->assertOnlyEventDispatched(OfferCreatedEvent::class, $this);
    }

    public function testTransferShouldMoveItemsAndMoney(): void
    {
        // Arrange
        $seller = new Trader('seller', new Inventory(), new Balance(0));
        $buyer = new Trader('buyer', new Inventory(), new Balance(1000));
        $offer = new Offer('offer', new Product('Graphics Card'), 100, 3, $seller);
        $offerRepo = new MemoryOfferRepository(new Offers($offer));
        $sut = $this->setupSut($offerRepo);
        // Act
        $sut->transact($buyer, $offer);
        // Assert
        $boughtItem = new Item(new Product('Graphics Card'), 3);
        $this->assertContainsEquals($boughtItem, $buyer->listInventory(), 'Buyer did not receive item!');
        $this->assertEquals($buyer->balance(), 700, 'Buyer did not transfer money!');
        $this->assertEquals($seller->balance(), 300, 'Selller did not receive money!');
        $this->assertEquals(new Offers(), $offerRepo->list(), 'Offer was not removed after transaction!');
    }

    public function testTransferShouldUpdateTradersAfterTransaction(): void
    {
        // Arrange
        $seller = new Trader('seller', new Inventory(), new Balance(0));
        $buyer = new Trader('buyer', new Inventory(), new Balance(1000));
        $offer = new Offer('offer', new Product('Graphics Card'), 100, 3, $seller);
        $offerRepo = new MemoryOfferRepository(new Offers($offer));
        $traderRepo = new MemoryTraderRepository($buyer, $seller);
        $sut = $this->setupSut($offerRepo, $traderRepo);
        // Act
        $sut->transact($buyer, $offer);
        // Assert
        $dbSeller = $traderRepo->find($seller->id());
        $dbBuyer = $traderRepo->find($buyer->id());
        $this->assertEquals($dbSeller, $seller, 'Seller was not updated in DB!');
        $this->assertEquals($dbBuyer, $buyer, 'Buyer was not updated in DB!');
    }

    public function testTransferShouldTriggerEvents(): void
    {
        // Arrange
        $spyEventDispatcher = new SpyEventDispatcher();
        $seller = new Trader('seller', new Inventory(), new Balance(0), $spyEventDispatcher);
        $buyer = new Trader('buyer', new Inventory(), new Balance(1000), $spyEventDispatcher);
        $offer = new Offer('offer', new Product('Graphics Card'), 100, 3, $seller);
        $offerRepo = new MemoryOfferRepository(new Offers($offer));
        $traderRepo = new MemoryTraderRepository($buyer, $seller);
        $sut = $this->setupSut($offerRepo, $traderRepo, $spyEventDispatcher);
        // Act
        $sut->transact($buyer, $offer);
        // Assert
        $expectedBalanceEvents = [
            new OfferSoldEvent($offer),
            new BalanceChangedEvent($seller, new Balance(300)),
            new BalanceChangedEvent($buyer, new Balance(700)),
        ];
        $spyEventDispatcher->assertEventsContain($expectedBalanceEvents, $this);
    }
}
