<?php

require_once __DIR__ . '/../vendor/autoload.php';

use CoiSA\Http\Application;
use CoiSA\Http\Handler\HttpPlugHandler;
use CoiSA\Http\Message\ServerRequestFactory;
use CoiSA\Http\Middleware\EchoBodyMiddleware;
use CoiSA\Http\Middleware\MiddlewareAggregator;
use CoiSA\Http\Middleware\SendHeadersMiddleware;
use Http\Client\Curl\Client as CurlClient;
use Middlewares\AccessLog;
use Middlewares\ClientIp;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$logger = new Logger('example');
$logger->pushHandler(new StreamHandler('php://stdout'));

$middleware = new MiddlewareAggregator(
    // Vendor
    new AccessLog($logger),
    new ClientIp(),

    // Self
    new EchoBodyMiddleware(),
    new SendHeadersMiddleware()
);

$curlClient = new CurlClient();
$handler = new HttpPlugHandler($curlClient);

$application = new Application($handler, $middleware);

$factory = new ServerRequestFactory();
$application->sendRequest(
    $factory->createServerRequest('GET', 'http://google.com')
);
