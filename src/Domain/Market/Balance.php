<?php

declare(strict_types=1);

namespace App\Domain\Market;

final class Balance
{
    private int $amount;

    public function __construct(int $initialAmount)
    {
        if ($initialAmount < 0) {
            throw new \InvalidArgumentException("Balance can't be less than zero!");
        }
        $this->amount = $initialAmount;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function withdraw(int $amount): Payment
    {
        if ($amount > $this->amount) {
            throw new InsufficientBalanceException($amount, $this->amount());
        }
        $this->amount -= $amount;

        return new Payment($amount);
    }

    public function deposit(Payment $payment): void
    {
        $this->amount += $payment->amount();
    }
}
