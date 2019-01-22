<?php

require_once __DIR__ . '/../vendor/autoload.php';

use CoiSA\Http\Application;
use CoiSA\Http\Handler\HttpPlugHandler;
use CoiSA\Http\Message\ServerRequestFactory;
use CoiSA\Http\Middleware\MiddlewareAggregator;
use Http\Client\Curl\Client as CurlClient;
use Middlewares\AccessLog;
use Middlewares\ClientIp;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$logger = new Logger('example');
$logger->pushHandler(new StreamHandler('php://stdout'));

$middleware = new MiddlewareAggregator(
    new AccessLog($logger),
    new ClientIp()
);

$curlClient = new CurlClient();
$handler = new HttpPlugHandler($curlClient);

$application = new Application($handler, $middleware);

$factory = new ServerRequestFactory();
$respose = $application->sendRequest(
    $factory->createServerRequest('GET', 'http://google.com')
);

echo $respose->getBody();
