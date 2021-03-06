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

use CoiSA\Http\Client\RequestHandlerClient;
use CoiSA\Http\Handler\MiddlewareHandler;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Dispatcher
 *
 * @package CoiSA\Http
 */
class Dispatcher implements DispatcherInterface
{
    /**
     * @var MiddlewareHandler
     */
    private $handler;

    /**
     * @var RequestHandlerClient
     */
    private $client;

    /**
     * Dispatcher constructor.
     *
     * @param RequestHandlerInterface            $handler
     * @param MiddlewareInterface                $middleware
     * @param null|ServerRequestFactoryInterface $serverRequestFactory
     */
    public function __construct(
        RequestHandlerInterface $handler,
        MiddlewareInterface $middleware,
        ServerRequestFactoryInterface $serverRequestFactory = null
    ) {
        $this->handler = new MiddlewareHandler(
            $middleware,
            $handler
        );

        $this->client  = new RequestHandlerClient(
            $this->handler,
            $serverRequestFactory
        );
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->client->sendRequest($request);
    }

    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->handler->handle($request);
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->handler->process($request, $handler);
    }
}
