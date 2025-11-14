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

use App\Schema\SavedSchema;
use App\Schema\SecretSchema;
use App\Service\SecretService;
use App\Service\SubService\UserAuth;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Swagger\Annotation as SA;
use Hyperf\Swagger\Request\SwaggerRequest;
use Swow\Psr7\Message\ResponsePlusInterface;

#[SA\HyperfServer('http')]
class SecretController extends Controller
{
    #[Inject]
    protected SecretService $service;

    #[SA\Post(path: '/secret/create', summary: '创建密码', tags: ['密码管理'])]
    #[SA\RequestBody(content: new SA\JsonContent(properties: [
        new SA\Property(property: 'secret', description: '密码', type: 'string', rules: 'required|string'),
    ]))]
    #[SA\Response(response: '200', content: new SA\JsonContent(ref: SavedSchema::class))]
    public function create(SwaggerRequest $request): ResponsePlusInterface
    {
        $secret = (string) $request->input('secret');
        $userId = UserAuth::instance()->getUserId();

        $result = $this->service->create($secret, $userId);

        return $this->response->success(new SavedSchema($result));
    }

    #[SA\Post(path: '/secret/check', summary: '验证密码', tags: ['密码管理'])]
    #[SA\RequestBody(content: new SA\JsonContent(properties: [
        new SA\Property(property: 'secret', description: '密码', type: 'string', rules: 'required|string'),
    ]))]
    #[SA\Response(response: '200', content: new SA\JsonContent(ref: SecretSchema::class))]
    public function check(SwaggerRequest $request)
    {
        $secret = (string) $request->input('secret');
        $userId = UserAuth::instance()->getUserId();

        $result = $this->service->check($secret, $userId);

        return $this->response->success($result);
    }
}
