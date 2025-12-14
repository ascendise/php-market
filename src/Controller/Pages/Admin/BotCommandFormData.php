<?php

declare(strict_types=1);

namespace App\Controller\Pages\Admin;

use App\Application\Bots\BotCommandDto;
use App\Application\Bots\BotType;
use App\Application\Bots\FrequencyDto;
use App\Domain\Bots\ConsumerArgs;
use App\Domain\Bots\ConsumeRate;
use App\Domain\Bots\ProducerArgs;
use App\Domain\Bots\ProduceRate;
use App\Domain\Bots\Range;
use App\Domain\Market\Product;

final class BotCommandFormData
{
    public function __construct(
        public readonly string $type,
        public readonly string $frequency,
        public readonly mixed $args,
    ) {
    }

    public function toDto(): BotCommandDto
    {
        $botType = BotType::from($this->type);
        $times = explode(':', $this->frequency); // hh:mm:ss
        $frequency = new FrequencyDto((int) $times[2], (int) $times[1], (int) $times[0]);
        $args = $this->getArgs($botType);

        return new BotCommandDto($botType, $args, $frequency);
    }

    private function getArgs(BotType $type): mixed
    {
        return match ($type) {
            BotType::Consumer => $this->getConsumerArgs(),
            BotType::Producer => $this->getProducerArgs(),
        };
    }

    private function getConsumerArgs(): ConsumerArgs
    {
        $consumeRates = array_map(fn ($e) => $this->toConsumeRate($e), $this->args['consumeRates']);
        $consumerArgs = new ConsumerArgs($consumeRates);

        return $consumerArgs;
    }

    private function toConsumeRate(mixed $e): ConsumeRate
    {
        $product = new Product($e['product']['name']);
        $budget = $this->toRange($e['budget']);
        $buyingVolume = $this->toRange($e['buyingVolume']);

        return new ConsumeRate(
            $product,
            $budget,
            $buyingVolume
        );
    }

    private function getProducerArgs(): ProducerArgs
    {
        $produceRates = array_map(fn ($e) => $this->toProduceRate($e), $this->args['produceRates']);
        $producerArgs = new ProducerArgs($produceRates);

        return $producerArgs;
    }

    private function toProduceRate(mixed $e): ProduceRate
    {
        $product = new Product($e['product']['name']);
        $tradingVolume = $this->toRange($e['tradingVolume']);
        $offerQuantity = $this->toRange($e['offerQuantity']);
        $pricePerItem = $this->toRange($e['pricePerItem']);

        return new ProduceRate(
            $product,
            $tradingVolume,
            $offerQuantity,
            $pricePerItem
        );
    }

    private function toRange(string $e): Range|int
    {
        if (!str_contains($e, '-')) {
            return (int) $e;
        }
        $range = explode('-', $e);
        $min = (int) $range[0];
        $max = (int) $range[1];

        return new Range($min, $max);
    }
}
