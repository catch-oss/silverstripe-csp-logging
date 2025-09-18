<?php

namespace Camspiers\CSP;

use SilverStripe\Control\Controller as SilverStripeController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;

/**
 * Class Controller
 * @package Camspiers\CSP
 */
class Controller extends SilverStripeController
{
    /**
     * This will be set automatically, as long as Controller is instantiated via Injector
     *
     * @var Logger
     */
    public $logger;

    private static $dependencies = [
        'logger' => '%$' . Logger::class,
    ];

    /**
     * @param HTTPRequest $request
     * @return HTTPResponse
     */
    public function index(HTTPRequest $request)
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
