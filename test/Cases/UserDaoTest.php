<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace HyperfTest\Cases;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\User;
use App\Service\Dao\UserDao;
use HyperfTest\HttpTestCase;
use Mockery;

/**
 * @internal
 * @covers \App\Service\Dao\UserDao
 */
class UserDaoTest extends HttpTestCase
{
    protected UserDao $userDao;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userDao = new UserDao();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testFirstReturnsUserWhenExists(): void
    {
        // Arrange
        $userId = 1;
        $mockUser = Mockery::mock(User::class);
        
        User::shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn($mockUser);

        // Act
        $result = $this->userDao->first($userId);

        // Assert
        $this->assertSame($mockUser, $result);
    }

    public function testFirstReturnsNullWhenUserNotFound(): void
    {
        // Arrange
        $userId = 999;
        
        User::shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn(null);

        // Act
        $result = $this->userDao->first($userId);

        // Assert
        $this->assertNull($result);
    }

    public function testFirstThrowsExceptionWhenUserNotFoundAndThrowIsTrue(): void
    {
        // Arrange
        $userId = 999;
        
        User::shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn(null);

        // Assert
        $this->expectException(BusinessException::class);
        $this->expectExceptionCode(ErrorCode::USER_NOT_EXIST);

        // Act
        $this->userDao->first($userId, true);
    }

    public function testFirstOrCreateReturnsExistingUser(): void
    {
        // Arrange
        $userId = 1;
        $mockUser = Mockery::mock(User::class);
        
        User::shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn($mockUser);

        // Act
        $result = $this->userDao->firstOrCreate($userId);

        // Assert
        $this->assertSame($mockUser, $result);
    }

    public function testFirstOrCreateCreatesNewUserWhenNotFound(): void
    {
        // Arrange
        $userId = 999;
        
        User::shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn(null);

        $mockUser = Mockery::mock(User::class);
        $mockUser->shouldReceive('save')->once()->andReturn(true);
        
        // Mock the User constructor to return our mock
        User::shouldReceive('__construct')->andReturnSelf();
        
        // Act
        $result = $this->userDao->firstOrCreate($userId);

        // Assert
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($userId, $result->id);
    }

    public function testFirstByOpenidReturnsUserWhenExists(): void
    {
        // Arrange
        $openid = 'test_openid_123';
        $mockUser = Mockery::mock(User::class);
        
        $mockQuery = Mockery::mock('stdClass');
        $mockQuery->shouldReceive('where')
            ->once()
            ->with('openid', $openid)
            ->andReturnSelf();
        $mockQuery->shouldReceive('first')
            ->once()
            ->andReturn($mockUser);

        User::shouldReceive('query')
            ->once()
            ->andReturn($mockQuery);

        // Act
        $result = $this->userDao->firstByOpenid($openid);

        // Assert
        $this->assertSame($mockUser, $result);
    }

    public function testFirstByOpenidReturnsNullWhenNotFound(): void
    {
        // Arrange
        $openid = 'non_existent_openid';
        
        $mockQuery = Mockery::mock('stdClass');
        $mockQuery->shouldReceive('where')
            ->once()
            ->with('openid', $openid)
            ->andReturnSelf();
        $mockQuery->shouldReceive('first')
            ->once()
            ->andReturn(null);

        User::shouldReceive('query')
            ->once()
            ->andReturn($mockQuery);

        // Act
        $result = $this->userDao->firstByOpenid($openid);

        // Assert
        $this->assertNull($result);
    }

    public function testFirstWithZeroUserId(): void
    {
        // Arrange
        $userId = 0;
        
        User::shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn(null);

        // Act
        $result = $this->userDao->first($userId);

        // Assert
        $this->assertNull($result);
    }

    public function testFirstWithNegativeUserId(): void
    {
        // Arrange
        $userId = -1;
        
        User::shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn(null);

        // Act
        $result = $this->userDao->first($userId);

        // Assert
        $this->assertNull($result);
    }

    public function testFirstOrCreateWithZeroUserId(): void
    {
        // Arrange
        $userId = 0;
        
        User::shouldReceive('find')
            ->once()
            ->with($userId)
            ->andReturn(null);

        $mockUser = Mockery::mock(User::class);
        $mockUser->shouldReceive('save')->once()->andReturn(true);
        
        User::shouldReceive('__construct')->andReturnSelf();

        // Act
        $result = $this->userDao->firstOrCreate($userId);

        // Assert
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($userId, $result->id);
    }

    public function testFirstByOpenidWithEmptyString(): void
    {
        // Arrange
        $openid = '';
        
        $mockQuery = Mockery::mock('stdClass');
        $mockQuery->shouldReceive('where')
            ->once()
            ->with('openid', $openid)
            ->andReturnSelf();
        $mockQuery->shouldReceive('first')
            ->once()
            ->andReturn(null);

        User::shouldReceive('query')
            ->once()
            ->andReturn($mockQuery);

        // Act
        $result = $this->userDao->firstByOpenid($openid);

        // Assert
        $this->assertNull($result);
    }
}