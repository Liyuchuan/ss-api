<?php

namespace App\Schema;

use Hyperf\Swagger\Annotation as SA;

#[SA\Schema()]
class LoginSchema
{

    #[SA\Property(type: 'string', title: 'token')]
    public string $token;
}
