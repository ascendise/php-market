<?php

declare(strict_types=1);

namespace App\Domain\Market;

final class InsufficientStockException extends \Exception
{
    private readonly int $askedQuantity;
    private readonly int $stockedQuantity;
    private readonly Product $product;

    public function __construct(
        int $askedQuantity,
        int $stockedQuantity,
        Product $product,
        int $code = 0,
        ?\Exception $previous = null,
    ) {
        $this->askedQuantity = $askedQuantity;
        $this->stockedQuantity = $stockedQuantity;
        $this->product = $product;
        $message = "Tried to create offer for {$product->name()} with quantity {$askedQuantity} 
        but stocked is {$stockedQuantity}";

        parent::__construct($message, $code, $previous);
    }

    public function askedQuantity(): int
    {
        return $this->askedQuantity;
    }

    public function stockedQuantity(): int
    {
        return $this->stockedQuantity;
    }

    public function product(): Product
    {
        return $this->product;
    }
}
