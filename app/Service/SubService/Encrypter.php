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

namespace App\Service\SubService;

use Han\Utils\Service;
use Illuminate\Encryption\Encrypter as EncryptionEncrypter;

class Encrypter extends Service
{
    public function encrypt(string $content, string $secret): string
    {
        $encrypt = new EncryptionEncrypter(md5($secret), 'AES-256-CBC');

        return $encrypt->encryptString($content);
    }

    public function decrypt(string $content, string $secret): string
    {
        $encrypt = new EncryptionEncrypter(md5($secret), 'AES-256-CBC');

        return $encrypt->decryptString($content);
    }
}
