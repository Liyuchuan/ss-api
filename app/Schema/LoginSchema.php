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

namespace App\Schema;

use Hyperf\Swagger\Annotation as SA;
use JsonSerializable;

#[SA\Schema()]
class LoginSchema implements JsonSerializable
{
    public function __construct(
        #[SA\Property(type: 'string', title: 'Token')]
        public string $token,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return ['token' => $this->token];
    }
}
