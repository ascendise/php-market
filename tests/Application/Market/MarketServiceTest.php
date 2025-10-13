<?php

declare(strict_types=1);

namespace App\Tests\Application\Market;

use App\Application\Market\CreatedOfferDto;
use App\Application\Market\CreateOfferDto;
use App\Application\Market\ItemDto;
use App\Application\Market\MarketServiceImpl;
use App\Application\Market\OfferDto;
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
        ?TraderRepository $traderRepository = null,
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
        $offerId = '0199b516-1865-7121-b57c-1f24e5ee703e';
        $storedOffers = new Offers(
            new Offer($offerId, new Product('Apple'), pricePerItem: 2, quantity: 5, seller: $seller)
        );
        $sut = $this->setupSut(new MemoryOfferRepository($storedOffers));
        // Act
        $offers = $sut->listOffers();
        // Assert
        $expectedOffers = new OffersDto(
            new OfferDto(
                Uuid::fromString($offerId),
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
        $offerRepository = new MemoryOfferRepository(
            new Offers(),
            new \ArrayIterator([Uuid::fromString('0199b524-3672-7650-bbd8-a4d2d4ba43f6')])
        );
        $traderId = Uuid::v7();
        $trader = new Trader($traderId->toString(), $inventory, new Balance(1000));
        $sut = $this->setupSut($offerRepository, new MemoryTraderRepository($trader));
        // Act
        $createOffer = new CreateOfferDto(
            new ProductDto('Apple'),
            quantity: 3,
            pricePerItem: 1
        );
        $response = $sut->createOffer($traderId, $createOffer);
        // Assert
        $expectedOffer = new OfferDto(
            Uuid::fromString('0199b524-3672-7650-bbd8-a4d2d4ba43f6'),
            new ProductDto('Apple'),
            quantity: 3,
            totalPrice: 3,
            sellerId: Uuid::fromString($trader->id())
        );
        $expectedResponse = new CreatedOfferDto(
            $expectedOffer,
            new OffersDto($expectedOffer)
        );
        $this->assertEquals($expectedResponse, $response);
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
        $_ = $sut->createOffer($traderId, $createOffer);
        // Assert
        $updatedTrader = $traderRepository->find($traderId->toString());
        $apples = array_find([...$updatedTrader->listInventory()], fn ($i) => 'Apple' == $i->product()->name());
        $this->assertEquals(2, $apples->quantity(), 'Trader inventory was not updated!');
    }

    public function testBuyOfferShouldReturnUpdatedTrader(): void
    {
        // Arrange
        $buyerId = Uuid::v7();
        $seller = new Trader(Uuid::v7()->toString(), new Inventory(), new Balance(0));
        $buyer = new Trader($buyerId->toString(), new Inventory(), new Balance(1000));
        $offerId = Uuid::v7();
        $offer = new Offer($offerId->toString(), new Product('Graphics Card'), 100, 3, $seller);
        $offerRepo = new MemoryOfferRepository(new Offers($offer));
        $traderRepo = new MemoryTraderRepository($seller, $buyer);
        $sut = $this->setupSut($offerRepo, $traderRepo);
        // Act
        $updatedBuyer = $sut->buyOffer($buyerId, $offerId);
        // Assert
        $this->assertEquals(700, $updatedBuyer->balance);
        $this->assertContainsEquals(new ItemDto(new ProductDto('Graphics Card'), 3), $updatedBuyer->inventory);
        $this->assertEmpty(iterator_to_array($offerRepo->list()), 'Offer was not removed');
    }

    public function testBuyOfferShouldUpdateTraders(): void
    {
        // Arrange
        $seller = new Trader(Uuid::v7()->toString(), new Inventory(), new Balance(0));
        $buyerId = Uuid::v7();
        $buyer = new Trader($buyerId->toString(), new Inventory(), new Balance(1000));
        $offerId = Uuid::v7();
        $offer = new Offer($offerId->toString(), new Product('Graphics Card'), 100, 3, $seller);
        $offerRepo = new MemoryOfferRepository(new Offers($offer));
        $traderRepo = new MemoryTraderRepository($seller, $buyer);
        $sut = $this->setupSut($offerRepo, $traderRepo);
        // Act
        $response = $sut->buyOffer($buyerId, $offerId);
        // Assert
        $updatedBuyer = $traderRepo->find($buyer->id());
        $updatedSeller = $traderRepo->find($seller->id());
        // Check if initial state of trader changed
        $this->assertEquals($seller, $updatedSeller); // Seller reference in offer, should be equal as in DB
        $this->assertNotEquals($buyer, $updatedBuyer); /* Buyer gets fetched by value
                                                        * initial buyer does not have updated state */
    }

    public function testBuyOfferShouldHandleTraderBuyingOwnOffer(): void
    {
        // Arrange
        $traderId = Uuid::v7();
        $trader = new Trader($traderId->toString(), new Inventory(), new Balance(1000));
        $offerId = Uuid::v7();
        $offer = new Offer($offerId->toString(), new Product('Graphics Card'), 100, 3, $trader);
        $offerRepo = new MemoryOfferRepository(new Offers($offer));
        $traderRepo = new MemoryTraderRepository($trader);
        $sut = $this->setupSut($offerRepo, $traderRepo);
        // Act
        $updatedBuyer = $sut->buyOffer($traderId, $offerId);
        // Assert
        $this->assertEquals(1000, $updatedBuyer->balance); // Balance should not change as Trader is paying itself
        $this->assertContainsEquals(new ItemDto(new ProductDto('Graphics Card'), 3), $updatedBuyer->inventory);
    }
}
