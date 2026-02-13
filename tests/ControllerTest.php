<?php

namespace Camspiers\CSP\Tests;

use Camspiers\CSP\Controller;
use Camspiers\CSP\Logger;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;

class ControllerTest extends SapphireTest
{
    protected $usesDatabase = false;

    public function testIndexLogsValidCspReport(): void
    {
        $logger = $this->createMock(Logger::class);
        Injector::inst()->registerService($logger, Logger::class);

        $controller = Controller::create();

        $body = json_encode([
            'csp-report' => [
                'document-uri' => 'http://example.com/signup.html',
                'referrer' => '',
                'blocked-uri' => 'http://example.com/css/style.css',
                'violated-directive' => 'style-src cdn.example.com',
                'original-policy' => "default-src 'none'; style-src cdn.example.com; report-uri /_/csp-reports",
            ],
        ]);

        $request = $this->buildRequest($body);

        $logger->expects($this->once())
            ->method('info')
            ->with(
                'Content-Security-Policy violation',
                $this->isType('array'),
            );

        $response = $controller->index($request);

        $this->assertSame(204, $response->getStatusCode());
        $this->assertSame('', $response->getBody());
    }

    public function testIndexIgnoresInvalidJson(): void
    {
        $logger = $this->createMock(Logger::class);
        Injector::inst()->registerService($logger, Logger::class);

        $controller = Controller::create();

        $request = $this->buildRequest('not json');

        $logger->expects($this->never())
            ->method('info');

        $response = $controller->index($request);

        $this->assertSame(204, $response->getStatusCode());
    }

    public function testIndexIgnoresMissingCspReport(): void
    {
        $logger = $this->createMock(Logger::class);
        Injector::inst()->registerService($logger, Logger::class);

        $controller = Controller::create();

        $request = $this->buildRequest(json_encode(['other-key' => 'value']));

        $logger->expects($this->never())
            ->method('info');

        $response = $controller->index($request);

        $this->assertSame(204, $response->getStatusCode());
    }

    public function testIndexHandlesEmptyBody(): void
    {
        $logger = $this->createMock(Logger::class);
        Injector::inst()->registerService($logger, Logger::class);

        $controller = Controller::create();

        $request = $this->buildRequest('');

        $logger->expects($this->never())
            ->method('info');

        $response = $controller->index($request);

        $this->assertSame(204, $response->getStatusCode());
    }

    private function buildRequest(string $body): \SilverStripe\Control\HTTPRequest
    {
        $request = new \SilverStripe\Control\HTTPRequest('POST', '/csp-report');
        $request->setBody($body);
        return $request;
    }
}
