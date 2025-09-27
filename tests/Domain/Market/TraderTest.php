<?php

declare(strict_types=1);

namespace App\Tests\Domain\Market;

use App\Domain\Market\Balance;
use App\Domain\Market\Offer;
use App\Domain\Market\Trader;
use App\Domain\Market\Product;
use App\Domain\Market\Inventory;
use App\Domain\Market\Item;
use App\Domain\Market\InsufficientStockException;
use App\Domain\Market\InsufficientBalanceException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TraderTest extends TestCase
{
    public function testSellShouldReturnOfferIfEnoughResources(): void
    {
        // Arrange
        $product = new Product('Banana');
        $inventory = new Inventory(new Item($product, 12));
        $sut = new Trader($inventory, new Balance(1000));
        // Act
        $offer = $sut->sell($product, price: 1, quantity: 5);
        // Assert
        $expected_offer = new Offer($product, 1, 5, $sut);
        $this->assertEquals($expected_offer, $offer, 'Wrong offer created!');
        $this->assertEquals(7, $inventory->quantityOf($product));
    }

    #[DataProvider("providerNoResourceCases")]
    public function testSellShouldThrowIfTraderHasInsufficientStock(
        Inventory $inventory,
        Product $product,
        int $quantity,
        int $stocked
    ): void {
        // Assert
        $expected_exception = new InsufficientStockException($quantity, $stocked, $product);
        $this->expectException(InsufficientStockException::class);
        $this->expectExceptionMessage($expected_exception->getMessage());
        // Arrange
        $sut = new Trader($inventory, new Balance(1000));
        // Act
        $_ = $sut->sell($product, 1, $quantity);
    }

    /**
     * @return array<int,array<int,mixed>>
     */
    public static function providerNoResourceCases(): array
    {
        $product = new Product('Apple');
        $lowQuantityInventory = new Inventory(new Item($product, quantity: 5));
        $wrongProductQuantity = new Inventory(new Item($product, quantity: 5));
        return array(
            array($lowQuantityInventory, $product, 10, 5), // Not enough product
            array($lowQuantityInventory, new Product('Banana'), 5, 0), // Wrong product
            array(new Inventory(), $product, 5, 0), // Empty inventory
        );
    }

    public function testBuyShouldAddOfferToInventoryWhenSuccesful(): void
    {
        // Arrange
        $sut = new Trader(new Inventory(), new Balance(1000));
        $computer = new Product("Computer");
        $seller = new StubTrader();
        $offer = new Offer($computer, 300, 3, $seller);
        // Act
        $sut->buy($offer);
        // Assert
        $expectedInventory = new Inventory(new Item($computer, 3));
        $this->assertEquals($expectedInventory, $sut->listInventory());
        $this->assertEquals(100, $sut->balance());
    }

    public function testBuyShouldTransferCurrencyToSellerWhenSuccessful(): void
    {
        // Arrange
        $sut = new Trader(new Inventory(), new Balance(1000));
        $computer = new Product("Computer");
        $seller = new Trader(new Inventory(), new Balance(0));
        $offer = new Offer($computer, 300, 3, $seller);
        // Act
        $sut->buy($offer);
        // Assert
        $this->assertEquals(900, $seller->balance());
    }

    public function testBuyShouldThrowWhenNotEnoughCurrencyToPayOffer(): void
    {
        // Assert
        $this->expectException(InsufficientBalanceException::class);
        // Arrange
        $sut = new Trader(new Inventory(), new Balance(100));
        $computer = new Product("Computer");
        $seller = new StubTrader();
        $offer = new Offer($computer, 300, 3, $seller);
        // Act
        $sut->buy($offer);
    }
}
