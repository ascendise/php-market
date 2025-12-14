<?php

declare(strict_types=1);

namespace App\Application\Bots;

use App\Domain\Bots\BotRunner;
use App\Domain\Bots\Schedule\BotFactory;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class RunBotsHandler
{
    public function __construct(
        private readonly BotFactory $factory,
        private readonly BotRunner $runner,
    ) {
    }

    public function __invoke(RunBotsMessage $message): void
    {
        $bots = array_map(fn ($bp) => $this->factory->create($bp), $message->botsToRun());
        $this->runner->run($bots);
    }
}
