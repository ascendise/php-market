<?php

declare(strict_types=1);

namespace App\Application\Bots;

use Symfony\Component\Uid\Uuid;

interface BotAdministrationService
{
    public function list(): BotsDto;

    public function findById(Uuid $id): ?BotDto;

    public function create(BotCommandDto $createBot): BotDto;

    public function update(Uuid $id, BotCommandDto $updateBot): ?BotDto;

    public function delete(Uuid $id): void;
}
