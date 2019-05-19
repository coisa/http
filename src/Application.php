<?php

/**
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace CoiSA\Http;

use CoiSA\Http\Handler\MiddlewareHandler;
use CoiSA\Http\Middleware\EchoBodyMiddleware;
use CoiSA\Http\Middleware\ErrorHandlerMiddleware;
use CoiSA\Http\Middleware\MiddlewareAggregator;
use CoiSA\Http\Middleware\SendHeadersMiddleware;
use Phly\EventDispatcher\EventDispatcher;
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
class Application implements ApplicationInterface
{
    /**
     * @var MiddlewareHandler
     */
    private $middleware;

    /**
     * Application constructor.
     *
     * @param DispatcherInterface $dispatcher
     * @param RequestHandlerInterface $errorHandler
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(
        DispatcherInterface $dispatcher,
        RequestHandlerInterface $errorHandler,
        EventDispatcher $eventDispatcher
    ) {
        $middleware = new MiddlewareAggregator(
            new SendHeadersMiddleware(),
            new EchoBodyMiddleware(),
            new ErrorHandlerMiddleware(
                $errorHandler,
                $eventDispatcher
            )
        );

        $this->middleware = new MiddlewareHandler(
            $middleware,
            $dispatcher
        );
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        // @FIXME convert RequestInterface to ServerRequestInterface
        return $this->handle($request);
    }

    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middleware->handle($request);
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->middleware->process($request, $handler);
    }
}
