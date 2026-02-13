<?php

namespace Camspiers\CSP\Tests;

use Camspiers\CSP\Controller;
use Camspiers\CSP\Logger;
use PHPUnit\Framework\TestCase;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;

class ControllerTest extends TestCase
{
    private Controller $controller;
    private Logger $logger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logger = $this->createMock(Logger::class);

        // Bypass SS Controller constructor which requires full framework bootstrap
        $ref = new \ReflectionClass(Controller::class);
        $this->controller = $ref->newInstanceWithoutConstructor();
        $this->controller->logger = $this->logger;

        $responseProp = new \ReflectionProperty(\SilverStripe\Control\Controller::class, 'response');
        $responseProp->setValue($this->controller, new HTTPResponse());
    }

    public function testIndexLogsValidCspReport(): void
    {
        $body = json_encode([
            'csp-report' => [
                'document-uri' => 'http://example.com/signup.html',
                'referrer' => '',
                'blocked-uri' => 'http://example.com/css/style.css',
                'violated-directive' => 'style-src cdn.example.com',
                'original-policy' => "default-src 'none'; style-src cdn.example.com; report-uri /_/csp-reports",
            ],
        ]);

        $request = new HTTPRequest('POST', '/csp-report');
        $request->setBody($body);

        $this->logger->expects($this->once())
            ->method('info')
            ->with(
                'Content-Security-Policy violation',
                $this->isType('array'),
            );

        $response = $this->controller->index($request);

        $this->assertSame(204, $response->getStatusCode());
        $this->assertSame('', $response->getBody());
    }

    public function testIndexIgnoresInvalidJson(): void
    {
        $request = new HTTPRequest('POST', '/csp-report');
        $request->setBody('not json');

        $this->logger->expects($this->never())
            ->method('info');

        $response = $this->controller->index($request);

        $this->assertSame(204, $response->getStatusCode());
    }

    public function testIndexIgnoresMissingCspReport(): void
    {
        $request = new HTTPRequest('POST', '/csp-report');
        $request->setBody(json_encode(['other-key' => 'value']));

        $this->logger->expects($this->never())
            ->method('info');

        $response = $this->controller->index($request);

        $this->assertSame(204, $response->getStatusCode());
    }

    public function testIndexHandlesEmptyBody(): void
    {
        $request = new HTTPRequest('POST', '/csp-report');
        $request->setBody('');

        $this->logger->expects($this->never())
            ->method('info');

        $response = $this->controller->index($request);

        $this->assertSame(204, $response->getStatusCode());
    }
}
