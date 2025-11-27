<?php

declare(strict_types=1);

namespace App\Application\Bots;

use Symfony\Component\Uid\Uuid;

interface BotAdministrationService
{
    public function list(): BotsDto;

    public function create(CreateBotDto $createBot): BotsDto;

    public function update(Uuid $id, CreateBotDto $updateBot): ?BotsDto;

    public function delete(Uuid $id): void;
}
