<?php

declare(strict_types=1);

namespace App\Tests\Domain\Market;

use App\Domain\Market\Offer;
use App\Domain\Market\Trader;
use App\Domain\Market\Product;
use App\Domain\Market\Inventory;
use App\Domain\Market\Item;
use PHPUnit\Framework\TestCase;

final class TraderTest extends TestCase
{
    public function testCreateOfferShouldReturnOfferIfEnoughResources(): void
    {
        // Arrange
        $product = new Product("Banana");
        $inventory = new Inventory(new Item($product, 12));
        $trader = new Trader($inventory, 0);
        // Act
        $offer = $trader->sell($product, price: 1, quantity: 5);
        // Assert
        $expected_offer = new Offer($product, 1, 5, $trader);
        $this->assertEquals($expected_offer, $offer, "Wrong offer created!");
        $this->assertEquals(7, $inventory->quantityOf($product));
    }
}
