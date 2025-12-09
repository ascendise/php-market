<?php

declare(strict_types=1);

namespace App\Tests\Application\Bots;

use App\Application\Bots\BotAdministrationServiceImpl;
use App\Application\Bots\BotCommandDto;
use App\Application\Bots\BotType;
use App\Application\Bots\FrequencyDto;
use App\Domain\Bots\ProducerArgs;
use App\Domain\Bots\Schedule\BotFactory;
use App\Domain\Bots\Schedule\InvalidBlueprintError;
use App\Domain\Bots\Schedule\InvalidBlueprintException;
use App\Domain\Market\Market;
use App\Tests\Domain\Bots\Schedule\MemoryBlueprintRepository;
use App\Tests\Domain\Market\MemoryOfferRepository;
use App\Tests\Domain\Market\MemoryTraderRepository;
use PHPUnit\Framework\TestCase;

final class BotAdministrationServiceTest extends TestCase
{
    private function setupMarket(): Market
    {
        $offerRepo = new MemoryOfferRepository();
        $traderRepo = new MemoryTraderRepository();
        $market = new Market($offerRepo, $traderRepo);

        return $market;
    }

    private function setupSut(): BotAdministrationServiceImpl
    {
        $blueprintRepo = new MemoryBlueprintRepository();
        $market = $this->setupMarket();
        $botFactory = new BotFactory($market);

        return new BotAdministrationServiceImpl($blueprintRepo, $botFactory);
    }

    public function testCreateShouldThrowWhenTryingToCreateInvalidBot(): void
    {
        // Arrange
        $sut = $this->setupSut();
        $invalidBot = new BotCommandDto(
            BotType::Producer,
            ['invalid' => 'args'],
            new FrequencyDto(seconds: 1)
        );
        // Assert
        $this->expectException(InvalidBlueprintException::class);
        $expectedException = new InvalidBlueprintException(
            $invalidBot->toEntity()->toBlueprint('stub'),
            InvalidBlueprintError::InvalidArgs
        );
        $this->expectExceptionMessage($expectedException->getMessage());
        // Act
        $sut->create($invalidBot);
    }

    public function testUpdateShouldThrowWhenTryingToUpdateToInvalidBot(): void
    {
        // Arrange
        $sut = $this->setupSut();
        $validBot = new BotCommandDto(
            BotType::Producer,
            new ProducerArgs([]),
            new FrequencyDto(seconds: 1)
        );
        $validBot = $sut->create($validBot);
        $invalidBot = new BotCommandDto(
            BotType::Producer,
            ['invalid' => 'args'],
            new FrequencyDto(seconds: 1)
        );
        // Assert
        $this->expectException(InvalidBlueprintException::class);
        $expectedException = new InvalidBlueprintException(
            $invalidBot->toEntity()->toBlueprint('stub'),
            InvalidBlueprintError::InvalidArgs
        );
        $this->expectExceptionMessage($expectedException->getMessage());
        // Act
        $sut->update($validBot->id, $invalidBot);
    }
}
