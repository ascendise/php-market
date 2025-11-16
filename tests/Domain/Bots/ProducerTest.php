<?php

declare(strict_types=1);

namespace App\Tests\Domain\Bots;

use App\Domain\Bots\Producer;
use App\Domain\Bots\ProduceRate;
use App\Domain\Bots\Range;
use App\Domain\Bots\RNG;
use App\Domain\Market\Market;
use App\Domain\Market\Offer;
use App\Domain\Market\Offers;
use App\Domain\Market\Product;
use App\Tests\Domain\Market\MemoryOfferRepository;
use App\Tests\Domain\Market\MemoryTraderRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class ProducerTest extends TestCase
{
    /**
     * @param array<int,Uuid> $uuidgen
     */
    private function setupMarket(array $uuidgen): Market
    {
        return new Market(
            new MemoryOfferRepository(new Offers(), new \ArrayIterator($uuidgen)),
            new MemoryTraderRepository()
        );
    }

    public function testActShouldSellItemsBasedOnFixedConfig(): void
    {
        // Arrange
        $uuidgen = array_fill(0, 3, Uuid::v7());
        $market = $this->setupMarket($uuidgen);
        $produceRate = new ProduceRate(new Product('Apple'), tradingVolume: 7, offerQuantity: 3, pricePerItem: 5);
        $sut = new Producer($market, [$produceRate], new RNG());
        // Act
        $sut->act();
        // Assert
        $newOffers = $market->listOffers();
        $expectedOffers = new Offers(
            new Offer($uuidgen[0]->toString(), new Product('Apple'), pricePerItem: 5, quantity: 3, seller: $sut),
            new Offer($uuidgen[1]->toString(), new Product('Apple'), pricePerItem: 5, quantity: 3, seller: $sut),
            new Offer($uuidgen[2]->toString(), new Product('Apple'), pricePerItem: 5, quantity: 1, seller: $sut),
        );
        $this->assertEquals($expectedOffers, $newOffers);
    }

    public function testActShouldSellItemBasedOnRangedConfig(): void
    {
        // Arrange
        $uuidgen = array_fill(0, 5, Uuid::v7());
        $market = $this->setupMarket($uuidgen);
        $produceRate = new ProduceRate(
            new Product('Apple'),
            tradingVolume: new Range(10, 20),
            offerQuantity: new Range(2, 5),
            pricePerItem: new Range(3, 5)
        );
        $sut = new Producer($market, [$produceRate], new FakeRNG(FakeRNGStrategy::UseMin));
        // Act
        $sut->act();
        // Assert
        $newOffers = $market->listOffers();
        $expectedOffers = new Offers(
            new Offer($uuidgen[0]->toString(), new Product('Apple'), pricePerItem: 3, quantity: 2, seller: $sut),
            new Offer($uuidgen[1]->toString(), new Product('Apple'), pricePerItem: 3, quantity: 2, seller: $sut),
            new Offer($uuidgen[2]->toString(), new Product('Apple'), pricePerItem: 3, quantity: 2, seller: $sut),
            new Offer($uuidgen[3]->toString(), new Product('Apple'), pricePerItem: 3, quantity: 2, seller: $sut),
            new Offer($uuidgen[4]->toString(), new Product('Apple'), pricePerItem: 3, quantity: 2, seller: $sut),
        );
        $this->assertEquals($expectedOffers, $newOffers);
    }
}
