<?php

declare(strict_types=1);

namespace App\Domain\Bots;

use App\Domain\Market\CreateOffer;
use App\Domain\Market\Market;
use App\Domain\Market\Payment;
use App\Domain\Market\Product;
use App\Domain\Market\Seller;
use App\Domain\Market\TraderRepository;

final class Producer implements Bot, Seller
{
    /**
     * @param array<int,ProduceRate> $produceRates
     */
    public function __construct(
        private readonly Market $market,
        private readonly array $produceRates,
        private readonly ?RNG $rng,
    ) {
    }

    public function act(): void
    {
        foreach ($this->produceRates as $produceRate) {
            $volume = Range::getValue($produceRate->tradingVolume(), $this->rng);
            while ($volume > 0) {
                $this->createOffer($produceRate, $volume);
            }
        }
    }

    private function createOffer(ProduceRate $produceRate, int &$volume): void
    {
        $quantity = min($volume, Range::getValue($produceRate->offerQuantity(), $this->rng));
        $offer = $this->sell(
            $produceRate->product(),
            Range::getValue($produceRate->pricePerItem(), $this->rng),
            $quantity
        );
        $this->market->createOffer($offer);
        $volume -= $offer->quantity();
    }

    public function sell(Product $product, int $pricePerItem, int $quantity): CreateOffer
    {
        return new CreateOffer($product, $pricePerItem, $quantity, $this);
    }

    public function receivePayment(Payment $payment): void
    {
        // Clankers don't get paid
    }

    public function id(): string
    {
        return TraderRepository::STUB_TRADER_ID;
    }
}
