<?php

declare(strict_types=1);

namespace App\Domain\Bots;

use Psr\Log\LoggerInterface;

final class BotRunner
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    /**
     * @param array<int,Bot> $bots
     */
    public function run(array $bots): void
    {
        foreach ($bots as $bot) {
            try {
                $bot->act();
            } catch (\Exception $ex) {
                $this->logger->error($ex->getMessage(), ['errBot' => $bot]);
            }
        }
    }
}
