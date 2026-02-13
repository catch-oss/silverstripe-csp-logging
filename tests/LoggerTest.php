<?php

namespace Camspiers\CSP\Tests;

use Camspiers\CSP\Logger;
use Monolog\Logger as MonologLogger;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    public function testExtendsMonologLogger(): void
    {
        $logger = new Logger('test');
        $this->assertInstanceOf(MonologLogger::class, $logger);
    }
}
