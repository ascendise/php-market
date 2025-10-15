<?php

declare(strict_types=1);

namespace App\Tests\Application\Auth;

use App\Application\Auth\AuthenticationServiceImpl;
use App\Application\Auth\CreateUserDto;
use App\Application\Auth\UserDto;
use App\Application\Auth\UserRepository;
use App\Application\Market\InventoryDto;
use App\Application\Market\TraderDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class AuthenticationServiceTest extends TestCase
{
    private function setupSut(UserRepository $userRepo): AuthenticationServiceImpl
    {
        $hasher = new FakeUserPasswordHasher();

        return new AuthenticationServiceImpl($hasher, $userRepo);
    }

    public function testCreateUserShouldAddNewUser(): void
    {
        // Arrange
        $noUsersRepo = new MemoryUserRepository();
        $sut = $this->setupSut($noUsersRepo);
        // Act
        $createUser = new CreateUserDto('email@example.com', 'pass123');
        $newUser = $sut->createUser($createUser);
        // Assert
        $expectedTrader = new TraderDto(Uuid::v7(), 0, new InventoryDto());
        $expectedUser = new UserDto('email@example.com', $expectedTrader);
        $this->assertEquals($expectedUser, $newUser);
    }
}
