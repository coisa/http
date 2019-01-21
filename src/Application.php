<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Http;

use CoiSA\Http\Handler\MiddlewareHandler;
use CoiSA\Http\Middleware\EchoBodyMiddleware;
use CoiSA\Http\Middleware\MiddlewareAggregator;
use CoiSA\Http\Middleware\RequestHandlerMiddleware;
use CoiSA\Http\Middleware\SendHeadersMiddleware;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Application
 *
 * @package CoiSA\Http
 */
class Application implements ClientInterface, RequestHandlerInterface, MiddlewareInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * Application constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(
        ClientInterface $client
    ) {
        $this->client = $client;
    }

    /**
     * @param RequestInterface $request
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->client->sendRequest($request);
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->sendRequest($request);
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $middlewareHandler = new MiddlewareHandler(
            new RequestHandlerMiddleware($this),
            $handler
        );

        return $middlewareHandler->handle($request);
    }

    /**
     * Run the application
     */
    public function run(): ResponseInterface
    {
        $request = $this->serverRequestFactory->createServerRequest(
            $_SERVER['REQUEST_METHOD'],
            'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}/{$_SERVER['REQUEST_URI']}",
            $_SERVER
        );

        $middleware = new MiddlewareAggregator(
            new SendHeadersMiddleware(),
            new EchoBodyMiddleware()
        );

        return $middleware->process($request, $this);
    }
}
