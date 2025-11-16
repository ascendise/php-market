<?php

declare(strict_types=1);

namespace App\Tests\Domain\Bots;

use App\Domain\Bots\Consumer;
use App\Domain\Bots\ConsumeRate;
use App\Domain\Bots\Range;
use App\Domain\Bots\RNG;
use App\Domain\Market\Balance;
use App\Domain\Market\Inventory;
use App\Domain\Market\Market;
use App\Domain\Market\Offer;
use App\Domain\Market\Offers;
use App\Domain\Market\Product;
use App\Domain\Market\Trader;
use App\Tests\Domain\Market\MemoryOfferRepository;
use App\Tests\Domain\Market\MemoryTraderRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class ConsumerTest extends TestCase
{
    /**
     * @param array<int,ConsumeRate> $consumeRates
     */
    private function setupSut(Market $market, array $consumeRates, ?RNG $rng = null): Consumer
    {
        $rng = $rng ?? new RNG();

        return new Consumer($market, $consumeRates, $rng);
    }

    /**
     * @param array<int, Trader> $traders
     */
    private function setupMarket(Offers $offers, array $traders): Market
    {
        return new Market(new MemoryOfferRepository($offers), new MemoryTraderRepository(...$traders));
    }

    private function setupSeller(int $balance = 0): Trader
    {
        return new Trader(Uuid::v7()->toString(), new Inventory(), new Balance($balance));
    }

    public function testActShouldBuyItemsBasedOnFixedConfig(): void
    {
        // Arrange
        $seller = $this->setupSeller();
        $apples = [];
        for ($i = 1; $i <= 19; ++$i) {
            $apples[] = new Offer(Uuid::v7()->toString(), new Product('Apple'), 2, 1, $seller);
        }
        $tooExpensiveApple = new Offer(Uuid::v7()->toString(), new Product('Apple'), 110, 50, $seller);
        $wrongProduct = new Offer(Uuid::v7()->toString(), new Product('Banana'), 1, 1, $seller);
        $market = $this->setupMarket(new Offers($wrongProduct, $tooExpensiveApple, ...$apples), [$seller]);
        $consumeRate = new ConsumeRate(
            new Product('Apple'),
            budget: 100,
            buyingVolume: 50
        );
        $consumer = $this->setupSut($market, [$consumeRate]);
        // Act
        $consumer->act();
        // Assert
        $leftOffers = $market->listOffers();
        $expectedLeftOffers = new Offers(
            $tooExpensiveApple,
            $wrongProduct
        );
        $this->assertEquals($expectedLeftOffers, $leftOffers);
    }

    public function testActShouldBuyItemsBasedOnRangedConfig(): void
    {
        // Arrange
        $seller = $this->setupSeller();
        $apples = [];
        for ($i = 1; $i <= 200; ++$i) {
            $apples[] = new Offer(
                Uuid::v7()->toString(),
                new Product('Apple'),
                pricePerItem: 1,
                quantity: 1,
                seller: $seller
            );
        }
        $market = $this->setupMarket(new Offers(...$apples), [$seller]);
        $consumeRate = new ConsumeRate(
            new Product('Apple'),
            budget: new Range(100, 200),
            buyingVolume: new Range(50, 100)
        );
        $consumer = $this->setupSut($market, [$consumeRate], new FakeRNG(FakeRNGStrategy::UseMin));
        // Act
        $consumer->act();
        // Assert
        $this->assertCount(150, $market->listOffers(), 'Did buy more product than specified!');
    }

    public function testActShouldTransferMoneyToSeller(): void
    {
        // Arrange
        $seller = new Trader('trader', new Inventory(), new Balance(0));
        $offer = new Offer(Uuid::v7()->toString(), new Product('Apple'), 100, 1, $seller);
        $market = $this->setupMarket(new Offers($offer), [$seller]);
        $consumeRate = new ConsumeRate(
            new Product('Apple'),
            budget: 100,
            buyingVolume: 1
        );
        $consumer = new Consumer($market, [$consumeRate], new RNG());
        // Act
        $consumer->act();
        // Assert
        $this->assertEquals(100, $seller->balance(), 'Money was not transferred to seller!');
    }
}
