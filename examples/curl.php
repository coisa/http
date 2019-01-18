<?php

require_once __DIR__ . '/../vendor/autoload.php';

use CoiSA\Http\Client;
use CoiSA\Http\Handler\CurlHandler;
use CoiSA\Http\Middleware\MiddlewareAggregator;
use Middlewares\AccessLog;
use Middlewares\ClientIp;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Nyholm\Psr7\Factory\Psr17Factory;

$logger = new Logger('example');
$logger->pushHandler(new StreamHandler('php://stdout'));

$middleware = new MiddlewareAggregator(
    new AccessLog($logger),
    new ClientIp()
);

$factory = new Psr17Factory();

$handler = new CurlHandler($factory);
$client = new Client($handler, $middleware);

$request = $factory->createRequest('GET', 'http://google.com');

return $client->sendRequest($request);
