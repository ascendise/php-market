<?php

namespace App\Tests\Domain\Market;

use App\Domain\Market\Inventory;
use App\Domain\Market\Item;
use App\Domain\Market\Product;
use PHPUnit\Framework\TestCase;

final class InventoryTest extends TestCase
{
    public function testConstructInitializesInventory(): void
    {
        // Arrange
        $item1 = new Item(new Product('Apple'), 1);
        $item2 = new Item(new Product('Banana'), 1);
        $item3 = new Item(new Product('Computer'), 1);
        // Act
        $sut = new Inventory($item1, $item2, $item3);
        // Assert
        $expected = ['Apple' => $item1, 'Banana' => $item2, 'Computer' => $item3];
        $actual = $sut->getIterator();
        $this->assertJsonStringEqualsJsonString(
            json_encode($expected),
            json_encode($actual)
        );
    }

    public function testAddShouldIncludeItemInInventory(): void
    {
        // Arrange
        $sut = new Inventory();
        $item = new Item(new Product('Apple'), 1);
        // Act
        $sut->add($item);
        // Assert
        $expected = new Inventory($item);
        $this->assertEquals($expected, $sut);
    }

    public function testAddShouldAddQuantityToExistingItem(): void
    {
        // Arrange
        $product = new Product('Apple');
        $sut = new Inventory(new Item($product, 3));
        // Act
        $sut->add(new Item($product, 8));
        // Assert
        $expected = new Inventory(new Item($product, 11));
        $this->assertEquals($expected, $sut);
    }
}
