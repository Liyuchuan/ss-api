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
use App\Schema\LoginSchema;
use App\Service\Dao\UserDao;
use App\Service\LoginService;
use App\Service\SubService\UserAuth;
use App\Service\SubService\WeChatService;
use HyperfTest\HttpTestCase;
use Liyuchuan\PltCommon\RPC\User\UserInterface;
use Mockery;

/**
 * @internal
 * @covers \App\Service\LoginService
 */
class LoginServiceTest extends HttpTestCase
{
    protected LoginService $loginService;
    protected WeChatService $weChatService;
    protected UserDao $userDao;
    protected UserInterface $userInterface;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->weChatService = Mockery::mock(WeChatService::class);
        $this->userDao = Mockery::mock(UserDao::class);
        $this->userInterface = Mockery::mock(UserInterface::class);
        
        $this->loginService = new LoginService();
        // Use reflection to inject dependencies
        $this->setPrivateProperty($this->loginService, 'chat', $this->weChatService);
        $this->setPrivateProperty($this->loginService, 'dao', $this->userDao);
        $this->setPrivateProperty($this->loginService, 'user', $this->userInterface);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testLoginSuccess(): void
    {
        // Arrange
        $code = 'valid_code_123';
        $openid = 'test_openid_456';
        $userId = 1;
        $token = 'jwt_token_789';
        
        $this->weChatService->shouldReceive('login')
            ->once()
            ->with($code)
            ->andReturn(['openid' => $openid]);
            
        $this->userInterface->shouldReceive('firstByCode')
            ->once()
            ->with($code, Mockery::any())
            ->andReturn(['id' => $userId]);
            
        $mockUser = Mockery::mock('stdClass');
        $this->userDao->shouldReceive('firstOrCreate')
            ->once()
            ->with($userId)
            ->andReturn($mockUser);
            
        $mockUserAuth = Mockery::mock(UserAuth::class);
        $mockUserAuth->shouldReceive('getToken')
            ->once()
            ->andReturn($token);
            
        UserAuth::shouldReceive('instance')
            ->once()
            ->andReturnSelf();
        UserAuth::shouldReceive('init')
            ->once()
            ->with($mockUser)
            ->andReturn($mockUserAuth);

        // Act
        $result = $this->loginService->login($code);

        // Assert
        $this->assertInstanceOf(LoginSchema::class, $result);
        $this->assertEquals($token, $result->token);
    }

    public function testLoginThrowsExceptionWhenOpenidIsEmpty(): void
    {
        // Arrange
        $code = 'invalid_code';
        
        $this->weChatService->shouldReceive('login')
            ->once()
            ->with($code)
            ->andReturn(['openid' => '']);

        // Assert
        $this->expectException(BusinessException::class);
        $this->expectExceptionCode(ErrorCode::OAUTH_FAILED);

        // Act
        $this->loginService->login($code);
    }

    public function testLoginThrowsExceptionWhenNoOpenid(): void
    {
        // Arrange
        $code = 'invalid_code';
        
        $this->weChatService->shouldReceive('login')
            ->once()
            ->with($code)
            ->andReturn([]);

        // Assert
        $this->expectException(BusinessException::class);
        $this->expectExceptionCode(ErrorCode::OAUTH_FAILED);

        // Act
        $this->loginService->login($code);
    }

    public function testLoginWithEmptyCode(): void
    {
        // Arrange
        $code = '';
        
        $this->weChatService->shouldReceive('login')
            ->once()
            ->with($code)
            ->andReturn(['openid' => '']);

        // Assert
        $this->expectException(BusinessException::class);
        $this->expectExceptionCode(ErrorCode::OAUTH_FAILED);

        // Act
        $this->loginService->login($code);
    }

    public function testLoginWithSpecialCharactersInCode(): void
    {
        // Arrange
        $code = 'code!@#$%^&*()_+-=[]{}|;:,.<>?';
        $openid = 'test_openid_special';
        $userId = 2;
        $token = 'jwt_token_special';
        
        $this->weChatService->shouldReceive('login')
            ->once()
            ->with($code)
            ->andReturn(['openid' => $openid]);
            
        $this->userInterface->shouldReceive('firstByCode')
            ->once()
            ->with($code, Mockery::any())
            ->andReturn(['id' => $userId]);
            
        $mockUser = Mockery::mock('stdClass');
        $this->userDao->shouldReceive('firstOrCreate')
            ->once()
            ->with($userId)
            ->andReturn($mockUser);
            
        $mockUserAuth = Mockery::mock(UserAuth::class);
        $mockUserAuth->shouldReceive('getToken')
            ->once()
            ->andReturn($token);
            
        UserAuth::shouldReceive('instance')
            ->once()
            ->andReturnSelf();
        UserAuth::shouldReceive('init')
            ->once()
            ->with($mockUser)
            ->andReturn($mockUserAuth);

        // Act
        $result = $this->loginService->login($code);

        // Assert
        $this->assertInstanceOf(LoginSchema::class, $result);
        $this->assertEquals($token, $result->token);
    }

    public function testLoginWithVeryLongCode(): void
    {
        // Arrange
        $code = str_repeat('a', 1000);
        $openid = 'test_openid_long';
        $userId = 3;
        $token = 'jwt_token_long';
        
        $this->weChatService->shouldReceive('login')
            ->once()
            ->with($code)
            ->andReturn(['openid' => $openid]);
            
        $this->userInterface->shouldReceive('firstByCode')
            ->once()
            ->with($code, Mockery::any())
            ->andReturn(['id' => $userId]);
            
        $mockUser = Mockery::mock('stdClass');
        $this->userDao->shouldReceive('firstOrCreate')
            ->once()
            ->with($userId)
            ->andReturn($mockUser);
            
        $mockUserAuth = Mockery::mock(UserAuth::class);
        $mockUserAuth->shouldReceive('getToken')
            ->once()
            ->andReturn($token);
            
        UserAuth::shouldReceive('instance')
            ->once()
            ->andReturnSelf();
        UserAuth::shouldReceive('init')
            ->once()
            ->with($mockUser)
            ->andReturn($mockUserAuth);

        // Act
        $result = $this->loginService->login($code);

        // Assert
        $this->assertInstanceOf(LoginSchema::class, $result);
        $this->assertEquals($token, $result->token);
    }

    public function testLoginWhenUserInterfaceReturnsEmptyArray(): void
    {
        // Arrange
        $code = 'valid_code';
        $openid = 'test_openid';
        
        $this->weChatService->shouldReceive('login')
            ->once()
            ->with($code)
            ->andReturn(['openid' => $openid]);
            
        $this->userInterface->shouldReceive('firstByCode')
            ->once()
            ->with($code, Mockery::any())
            ->andReturn([]);

        // Assert
        $this->expectException(BusinessException::class);
        $this->expectExceptionCode(ErrorCode::USER_NOT_EXIST);

        // Act
        $this->loginService->login($code);
    }

    /**
     * Helper method to set private properties
     */
    private function setPrivateProperty(object $object, string $property, $value): void
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}