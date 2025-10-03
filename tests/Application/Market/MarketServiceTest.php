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
use App\Domain\Market\Offers;
use App\Domain\Market\Product;
use App\Domain\Market\Trader;
use App\Tests\Domain\Market\MemoryOfferRepository;
use App\Tests\Domain\Market\MemoryTraderRepository;
use App\Tests\Domain\Market\StubTrader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class MarketServiceTest extends TestCase
{
    private function setupSut(?Offers $initOffers, Trader ...$traders): MarketServiceImpl
    {
        $offersRepository = new MemoryOfferRepository($initOffers ?? []);
        $market = new Market($offersRepository);
        $traderRepository = new MemoryTraderRepository($traders);
        return new MarketServiceImpl($market, $traderRepository);
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

    public function testCreateOfferShouldAddOfferToDatabase(): void
    {
        // Arrange
        $inventory = new Inventory(new Item(new Product('Apple'), 5));
        $trader = new Trader(Uuid::v7(), $inventory, new Balance(1000));
        $sut = $this->setupSut(new Offers(), $trader);
        // Act
        $createOffer = new CreateOfferDto();
        // Assert
    }
}
