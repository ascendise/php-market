<?php

declare(strict_types=1);

namespace App\Controller\Pages\Admin;

use App\Application\Bots\BotCommandDto;
use App\Application\Bots\BotType;
use App\Application\Bots\FrequencyDto;
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
        $produceRates = array_map(fn ($e) => $this->toProduceRate($e), $this->args['produceRates']);
        $producerArgs = new ProducerArgs($produceRates);

        return new BotCommandDto($botType, $producerArgs, $frequency);
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
