<?php

declare(strict_types=1);

namespace App\Tests\Application\Market;

use App\Application\Market\MarketServiceImpl;
use App\Application\Market\OfferDto;
use App\Application\Market\CreateOfferDto;
use App\Application\Market\OffersDto;
use App\Application\Market\ProductDto;
use App\Domain\Market\Balance;
use App\Domain\Market\Inventory;
use App\Domain\Market\Item;
use App\Domain\Market\Market;
use App\Domain\Market\Offer;
use App\Domain\Market\OfferRepository;
use App\Domain\Market\Offers;
use App\Domain\Market\Product;
use App\Domain\Market\Trader;
use App\Domain\Market\TraderRepository;
use App\Tests\Domain\Market\MemoryOfferRepository;
use App\Tests\Domain\Market\MemoryTraderRepository;
use App\Tests\Domain\Market\StubTrader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class MarketServiceTest extends TestCase
{
    private function setupSut(
        ?OfferRepository $offerRepository = null,
        ?TraderRepository $traderRepository = null
    ): MarketServiceImpl {
        $offerRepository = $offerRepository ?? new MemoryOfferRepository(new Offers());
        $market = new Market($offerRepository);
        $traderRepository = $traderRepository ?? new MemoryTraderRepository();
        return new MarketServiceImpl($market, $traderRepository);
    }

    public function testListOffersShouldReturnAllOffers(): void
    {
        // Arrange
        $seller = new StubTrader();
        $storedOffers = new Offers(
            new Offer(new Product("Apple"), pricePerItem: 2, quantity: 5, seller: $seller)
        );
        $sut = $this->setupSut(new MemoryOfferRepository($storedOffers));
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

    public function testCreateOfferShouldAddOfferToDatabase(): void
    {
        // Arrange
        $inventory = new Inventory(new Item(new Product('Apple'), quantity: 5));
        $offerRepository = new MemoryOfferRepository(new Offers());
        $traderId = Uuid::v7();
        $trader = new Trader($traderId->toString(), $inventory, new Balance(1000));
        $sut = $this->setupSut($offerRepository, new MemoryTraderRepository($trader));
        // Act
        $createOffer = new CreateOfferDto(
            new ProductDto('Apple'),
            quantity: 3,
            pricePerItem: 1
        );
        $createdOffer = $sut->createOffer($traderId, $createOffer);
        // Assert
        $expectedOffer = new OfferDto(
            new ProductDto('Apple'),
            quantity: 3,
            totalPrice: 3,
            sellerId: Uuid::fromString($trader->id())
        );
        $this->assertEquals($expectedOffer, $createdOffer);
        $this->assertCount(1, $offerRepository->list());
    }

    public function testCreateOfferShouldPersistTraderState(): void
    {
        // Arrange
        $inventory = new Inventory(new Item(new Product('Apple'), quantity: 5));
        $traderId = Uuid::v7();
        $trader = new Trader($traderId->toString(), $inventory, new Balance(1000));
        $traderRepository = new MemoryTraderRepository($trader);
        $sut = $this->setupSut(traderRepository: $traderRepository);
        // Act
        $createOffer = new CreateOfferDto(
            new ProductDto('Apple'),
            quantity: 3,
            pricePerItem: 1
        );
        $createdOffer = $sut->createOffer($traderId, $createOffer);
        // Assert
        $updatedTrader = $traderRepository->findTrader($traderId->toString());
        $apples = array_find([...$updatedTrader->listInventory()], fn ($i) => $i->product()->name() == 'Apple');
        $this->assertEquals(2, $apples->quantity(), 'Trader inventory was not updated!');
    }
}
