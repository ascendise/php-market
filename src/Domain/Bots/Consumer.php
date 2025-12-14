<?php

declare(strict_types=1);

namespace App\Domain\Bots;

use App\Domain\Market\Balance;
use App\Domain\Market\Buyer;
use App\Domain\Market\Market;
use App\Domain\Market\Offer;
use App\Domain\Market\Payment;
use App\Domain\Market\TraderRepository;

final class Consumer implements Bot, Buyer
{
    public function __construct(
        private readonly Market $market,
        private readonly ConsumerArgs $args,
        private readonly RNG $rng,
    ) {
    }

    public function act(): void
    {
        $consumeRates = $this->args->consumeRates();
        foreach ($consumeRates as $consumeRate) {
            $this->buyProducts($consumeRate);
        }
    }

    private function buyProducts(ConsumeRate $rate): void
    {
        $offers = $this->fetchMatchingOffers($rate);
        $balance = new Balance(Range::getValue($rate->budget, $this->rng));
        $volume = Range::getValue($rate->buyingVolume, $this->rng);
        foreach ($offers as $offer) {
            $this->buyOffer($offer, $balance, $volume);
            $isOutOfFunds = 0 == $balance->amount() || 0 == $volume;
            if ($isOutOfFunds) {
                break;
            }
        }
    }

    /**
     * @return array<string, Offer>
     */
    private function fetchMatchingOffers(ConsumeRate $rate): array
    {
        $offers = $this->market->listOffers();
        $offers = array_filter(
            iterator_to_array($offers),
            fn ($o) => $o->product()->name == $rate->product->name
        );

        return $offers;
    }

    private function buyOffer(Offer $offer, Balance &$balance, int &$volume): void
    {
        if ($balance->amount() >= $offer->totalPrice()) {
            $_ = $balance->withdraw($offer->totalPrice());
            $this->market->transact($this, $offer);
            $volume -= $offer->quantity();
        }
    }

    public function buy(Offer $offer): void
    {
        $payment = new Payment($offer->totalPrice());
        $offer->seller()->receivePayment($payment);
    }

    public function id(): string
    {
        return TraderRepository::STUB_TRADER_ID;
    }
}
