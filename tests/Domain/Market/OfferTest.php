<?php

declare(strict_types=1);

namespace App\Tests\Domain\Market;

use App\Domain\Market\Offer;
use App\Domain\Market\Product;
use App\Domain\Market\Seller;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class OfferTest extends TestCase
{
    private static function setupSeller(): Seller
    {
        return new StubTrader();
    }

    #[DataProvider('invalidNumberProvider')]
    public function testConstructShouldRejectNegativePrice(int $invalidPrice): void
    {
        // Assert
        $this->expectException(\InvalidArgumentException::class);
        // Arrange
        $stubSeller = OfferTest::setupSeller();
        // Act
        $_ = new Offer('id', new Product('The C Programming Language'), $invalidPrice, 5, $stubSeller);
    }

    /**
     * @return array<int,list<int>>
     */
    public static function invalidNumberProvider(): array
    {
        return [
            [0],
            [-1],
            [-512],
        ];
    }

    #[DataProvider('invalidNumberProvider')]
    public function testConstructShouldRejectNegativeQuantity(int $invalidQuantity): void
    {
        // Assert
        $this->expectException(\InvalidArgumentException::class);
        // Arrange
        $stubSeller = OfferTest::setupSeller();
        // Act
        $_ = new Offer('id', new Product('The C Programming Language'), 25, $invalidQuantity, $stubSeller);
    }
}
