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

namespace App\Controller;

use App\Schema\UserSchema;
use App\Service\SubService\UserAuth;
use App\Service\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation as SA;

#[SA\HyperfServer('http')]
class UserController extends Controller
{
    #[Inject]
    protected UserService $service;

    #[SA\Get(path: '/user/info', summary: '用户信息', tags: ['注册登录'])]
    #[SA\Response(response: '200', content: new SA\JsonContent(ref: UserSchema::class))]
    public function info()
    {
        $userId = UserAuth::instance()->getUserId();

        $result = $this->service->info($userId);
        return $this->response->success($result);
    }
}
