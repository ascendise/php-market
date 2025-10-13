<?php

declare(strict_types=1);

namespace App\Domain\Market;

final class InsufficientBalanceException extends \Exception
{
    private readonly int $askedPrice;
    private readonly int $balance;

    public function __construct(
        int $askedPrice,
        int $balance,
        int $code = 0,
        ?\Exception $previous = null,
    ) {
        $this->askedPrice = $askedPrice;
        $this->balance = $balance;
        $message = "You are too poor to pay this offer. Price: $askedPrice < Balance: $balance";

        parent::__construct($message, $code, $previous);
    }

    public function askedPrice(): int
    {
        return $this->askedPrice;
    }

    public function balance(): int
    {
        return $this->balance;
    }
}
