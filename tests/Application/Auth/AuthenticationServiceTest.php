<?php

declare(strict_types=1);

namespace App\Tests\Application\Auth;

use App\Application\Auth\AuthenticationServiceImpl;
use App\Application\Auth\InitState;
use App\Application\Auth\LoginDto;
use App\Application\Auth\RegistrationError;
use App\Application\Auth\RegistrationException;
use App\Application\Auth\TraderInitializer;
use App\Application\Auth\UserCommandDto;
use App\Application\Auth\UserDto;
use App\Application\Auth\UserRepository;
use App\Application\Auth\UserTraderInitializer;
use App\Application\Market\InventoryDto;
use App\Application\Market\ItemDto;
use App\Application\Market\ProductDto;
use App\Application\Market\TraderDto;
use App\Domain\Market\Inventory;
use App\Domain\Market\Item;
use App\Domain\Market\Product;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class AuthenticationServiceTest extends TestCase
{
    private function setupSut(
        UserRepository $userRepo,
        ?TraderInitializer $traderInitializer = null,
    ): AuthenticationServiceImpl {
        $hasher = new FakeUserPasswordHasher();
        if (null == $traderInitializer) {
            $traderInitializer = new UserTraderInitializer(new InitState(0, new Inventory()));
        }

        return new AuthenticationServiceImpl($userRepo, $hasher, $traderInitializer);
    }

    public function testCreateUserShouldAddNewUser(): void
    {
        // Arrange
        $userId = Uuid::v7();
        $traderId = Uuid::v7();
        $userRepo = new MemoryUserRepository(new \ArrayIterator([$userId, $traderId]));
        $sut = $this->setupSut($userRepo);
        // Act
        $createUser = new UserCommandDto('email@example.com', 'pass123');
        $newUser = $sut->createUser($createUser);
        // Assert
        $expectedTrader = new TraderDto($traderId, 0, new InventoryDto());
        $expectedUser = new UserDto($userId, 'email@example.com', $expectedTrader);
        $this->assertEquals($expectedUser, $newUser);
        $dbUsers = $userRepo->list();
        $this->assertCount(1, $dbUsers);
        $dbUser = $dbUsers[array_key_first($dbUsers)];
        $this->assertTrue(
            FakeUserPasswordHasher::assertIsHashed($dbUser->getPassword(), 'pass123'),
            'Password stored in plaintext!'
        );
    }

    public function testCreateUserShouldInitializeTrader(): void
    {
        // Arrange
        $userId = Uuid::v7();
        $traderId = Uuid::v7();
        $userRepo = new MemoryUserRepository(new \ArrayIterator([$userId, $traderId]));
        $initState = new InitState(
            1337,
            new Inventory(
                new Item(new Product('Apple'), 3)
            )
        );
        $traderInitializer = new UserTraderInitializer($initState);
        $sut = $this->setupSut($userRepo, $traderInitializer);
        // Act
        $createUser = new UserCommandDto('email@example.com', 'pass123');
        $newUser = $sut->createUser($createUser);
        // Assert
        $expectedTrader = new TraderDto(
            $traderId,
            1337,
            new InventoryDto(
                new ItemDto(new ProductDto('Apple'), 3)
            )
        );
        $this->assertEquals($expectedTrader, $newUser->trader);
    }

    public function testCreateUserShouldThrowIfUserAlreadyExists(): void
    {
        // Assert
        $this->expectException(RegistrationException::class);
        $this->expectExceptionCode(RegistrationError::UserAlreadyExists->value);
        // Arrange
        $userId = Uuid::v7();
        $traderId = Uuid::v7();
        $userRepo = new MemoryUserRepository(new \ArrayIterator([$userId, $traderId]));
        $sut = $this->setupSut($userRepo);
        // Act
        $createUser = new UserCommandDto('email@example.com', 'pass123');
        $newUser = $sut->createUser($createUser);
        $_ = $sut->createUser($createUser); // Tries to create duplicate user and throws
    }

    public function testLoginUserShouldReturnUserForMatchingCredentials(): void
    {
        // Arrange
        $userId = Uuid::v7();
        $traderId = Uuid::v7();
        $userRepo = new MemoryUserRepository(new \ArrayIterator([$userId, $traderId]));
        $sut = $this->setupSut($userRepo);
        $createUser = new UserCommandDto('email@example.com', 'pass123');
        $_ = $sut->createUser($createUser);
        // Act
        $user = $sut->login(new LoginDto('email@example.com', 'pass123'));
        // Assert
        $this->assertNotNull($user, 'Login failed!');
        $expectedTrader = new TraderDto($traderId, 0, new InventoryDto());
        $expectedUser = new UserDto($userId, 'email@example.com', $expectedTrader);
        $this->assertEquals($expectedUser, $user);
    }

    #[DataProvider('invalidCredentialsProvider')]
    public function testLoginUserShouldReturnNullForMismatchedCredentials(LoginDto $wrongCredentials): void
    {
        // Arrange
        $userId = Uuid::v7();
        $traderId = Uuid::v7();
        $userRepo = new MemoryUserRepository(new \ArrayIterator([$userId, $traderId]));
        $sut = $this->setupSut($userRepo);
        $createUser = new UserCommandDto('email@example.com', 'pass123');
        $_ = $sut->createUser($createUser);
        // Act
        $user = $sut->login($wrongCredentials);
        // Assert
        $this->assertNull($user, 'Login succeeded unexpectedly!');
    }

    /**
     * @return array<array<LoginDto>>
     */
    public static function invalidCredentialsProvider(): array
    {
        return [
            [new LoginDto('email@example.com', 'wrongpasshehe')], // Wrong password
            [new LoginDto('heyman@whatsmymail.com', 'pass123')], // Wrong email
        ];
    }
}
