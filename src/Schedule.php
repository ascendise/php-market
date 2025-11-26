<?php

declare(strict_types=1);

namespace App;

use App\Application\Bots\BotBlueprint;
use App\Application\Bots\RunBotsMessage;
use App\Domain\Bots\Consumer;
use App\Domain\Bots\ConsumeRate;
use App\Domain\Bots\Producer;
use App\Domain\Bots\ProduceRate;
use App\Domain\Bots\Range;
use App\Domain\Market\Product;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule as SymfonySchedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsSchedule]
class Schedule implements ScheduleProviderInterface
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    public function getSchedule(): SymfonySchedule
    {
        $producer = new BotBlueprint(
            'bot1',
            Producer::class,
            [[new ProduceRate(
                new Product('Apple'),
                tradingVolume: new Range(80, 100),
                offerQuantity: new Range(5, 20),
                pricePerItem: new Range(1, 3)
            )]],
            '3 seconds'
        );
        $producerTrigger = RecurringMessage::every($producer->frequency(), new RunBotsMessage([$producer]));
        $consumer = new BotBlueprint(
            'bot2',
            Consumer::class,
            [[new ConsumeRate(
                new Product('Apple'),
                budget: new Range(100, 300),
                buyingVolume: new Range(40, 120)
            )]],
            '5 seconds'
        );
        $consumerTrigger = RecurringMessage::every($consumer->frequency(), new RunBotsMessage([$consumer]));

        return (new SymfonySchedule())
            ->stateful($this->cache) // ensure missed tasks are executed
            ->processOnlyLastMissedRun(true) // ensure only last missed task is run
            ->add($producerTrigger)
            ->add($consumerTrigger);
    }
}
