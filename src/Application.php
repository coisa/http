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
use CoiSA\Http\Handler\PsrHttpClientHandler;
use CoiSA\Http\Middleware\EchoBodyMiddleware;
use CoiSA\Http\Middleware\MiddlewareAggregator;
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
class Application implements RequestHandlerInterface, MiddlewareInterface
{
    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * Application constructor.
     *
     * @param PsrHttpClientHandler $handler
     * @param MiddlewareAggregator $middlewares
     */
    public function __construct(
        RequestHandlerInterface $handler,
        MiddlewareInterface $middleware = null
    ) {
        $middleware = new MiddlewareAggregator(
            new SendHeadersMiddleware(),
            new EchoBodyMiddleware(),
            $middleware
        );

        $this->handler = new MiddlewareHandler(
            $middleware,
            $handler
        );
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->handler->handle($request);
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->handler->process($request, $handler);
    }
}
