<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\LoginService;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;
use function hyperf\Di;
use function Hyperf\Support\make;

#[Command]
class TestCommand extends HyperfCommand
{
    public function __construct(protected ContainerInterface $container)
    {
        parent::__construct('test');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('测试脚本');
    }

    public function handle()
    {
        // make(LoginService::class)->dump();
    }
}
