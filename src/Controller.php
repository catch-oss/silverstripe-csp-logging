<?php

namespace Camspiers\CSP;

use SilverStripe\Control\Controller as SilverStripeController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;

class Controller extends SilverStripeController
{
    public Logger $logger;

    private static array $dependencies = [
        'logger' => '%$' . Logger::class,
    ];

    public function index(HTTPRequest $request): HTTPResponse
    {
        $this->response->setStatusCode(204);
        $this->response->setBody('');

        $report = json_decode($request->getBody(), true);

        if (is_array($report) && isset($report['csp-report'])) {

            $this->logger->info(
                'Content-Security-Policy violation',
                $report['csp-report']
            );
        }

        return $this->response;
    }
}
