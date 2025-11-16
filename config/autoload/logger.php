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
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

return [
    'default' => [
        'handler' => [
            // 'class' => RotatingFileHandler::class,
            'class' => StreamHandler::class,
            'constructor' => [
                // 'stream' => BASE_PATH . '/runtime/logs/hyperf.log',
                'stream' => 'php://output',
                'level' => Level::Debug,
            ],
        ],
        // 'formatter' => [
        //     'class' => JsonFormatter::class,
        //     'constructor' => [],
        // ],
        'formatter' => [
            'class' => LineFormatter::class,
            'constructor' => [
                'format' => null,
                'dateFormat' => null,
                'allowInlineLineBreaks' => true,
            ],
        ],
    ],
];
