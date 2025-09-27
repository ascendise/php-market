<?php

declare(strict_types=1);

namespace App\Tests\Domain\Market;

use App\Domain\Market\Balance;
use App\Domain\Market\InsufficientBalanceException;
use App\Domain\Market\Payment;
use PHPUnit\Framework\TestCase;

final class BalanceTest extends TestCase
{
    public function testWithdrawShouldReduceAmountAndReturnRemovedCurrency(): void
    {
        // Arrange
        $sut = new Balance(100);
        // Act
        $payment = $sut->withdraw(58);
        // Assert
        $this->assertEquals(42, $sut->amount());
        $this->assertEquals(new Payment(58), $payment);
    }

    public function testWithdrawShouldThrowWhenTryingToRemoveMoreCurrencyThanOwned(): void
    {
        // Assert
        $expected_exception = new InsufficientBalanceException(101, 100);
        $this->expectException(InsufficientBalanceException::class);
        $this->expectExceptionMessage($expected_exception->getMessage());
        // Arrange
        $sut = new Balance(100);
        // Act
        $payment = $sut->withdraw(101);
    }

    public function testDepositShouldAddPaymentToBalance(): void
    {
        // Arrange
        $sut = new Balance(100);
        // Act
        $sut->deposit(new Payment(1237));
        // Assert
        $this->assertEquals(1337, $sut->amount());
    }
}
