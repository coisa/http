<?php

require_once __DIR__ . '/../vendor/autoload.php';

use CoiSA\Http\Handler\HttpPlugHandler;
use CoiSA\Http\Handler\MiddlewareHandler;
use CoiSA\Http\Middleware\MiddlewareAggregator;
use CoiSA\Http\PsrHttpClient;
use Http\Client\Curl\Client as CurlClient;
use Middlewares\AccessLog;
use Middlewares\ClientIp;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Nyholm\Psr7\Factory\Psr17Factory;

$logger = new Logger('example');
$logger->pushHandler(new StreamHandler('php://stdout'));

$factory = new Psr17Factory();
$request = $factory->createRequest('GET', 'http://google.com');

$middleware = new MiddlewareAggregator(
    new AccessLog($logger),
    new ClientIp()
);

$curlClient = new CurlClient();
$handler = new HttpPlugHandler($curlClient);

$dispatcher = new MiddlewareHandler(
    $middleware,
    $handler
);

$client = new PsrHttpClient($dispatcher);
$respose = $client->sendRequest($request);

echo $respose->getBody();
