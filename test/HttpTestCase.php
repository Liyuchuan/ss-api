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

namespace HyperfTest;

use App\Service\SubService\UserAuth;
use App\Service\SubService\WeChatService;
use Hyperf\Di\Container;
use Hyperf\Testing\Client;
use Mockery;
use PHPUnit\Framework\TestCase;

use function Hyperf\Support\make;

/**
 * Class HttpTestCase.
 * @method get($uri, $data = [], $headers = [])
 * @method post($uri, $data = [], $headers = [])
 * @method json($uri, $data = [], $headers = [])
 * @method file($uri, $data = [], $headers = [])
 * @method request($method, $path, $options = [])
 */
abstract class HttpTestCase extends TestCase
{
    /**
     * @var Client
     */
    protected $client;

    protected static bool $init = false;

    protected static string $token = '';

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = make(Client::class);
    }

    public function __call($name, $arguments)
    {
        return $this->client->{$name}(...$arguments);
    }

    public function setUp(): void
    {
        parent::setUp();

        if (! self::$init) {
            self::$init = true;

            /** @var Container $container */
            $container = di();
            $container->set(WeChatService::class, $chat = Mockery::mock(WeChatService::class));
            $chat->shouldReceive('login')->with('1234567890')->andReturn(['openid' => 'oDA2A1x56y3kqdwVLqCP_WqcI0x0']);

            $res = $this->json('/login', [
                'code' => '1234567890',
            ]);

            self::$token = $res['data']['token'];

            $this->json('/secret/check', [
                'secret' => '666',
            ], [
                UserAuth::X_TOKEN => self::$token,
            ]);
        }
    }
}
