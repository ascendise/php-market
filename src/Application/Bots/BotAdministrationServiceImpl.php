<?php

declare(strict_types=1);

namespace App\Application\Bots;

use Symfony\Component\Uid\Uuid;

final class BotAdministrationServiceImpl implements BotAdministrationService
{
    public function list(): BotsDto
    {
        throw new \Exception('not implemented');
    }

    public function create(CreateBotDto $createBot): BotsDto
    {
        throw new \Exception('not implemented');
    }

    public function update(Uuid $id, CreateBotDto $updateBot): ?BotsDto
    {
        throw new \Exception('not implemented');
    }

    public function delete(Uuid $id): void
    {
        throw new \Exception('not implemented');
    }
}
