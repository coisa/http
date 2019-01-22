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
use CoiSA\Http\Handler\ServerRequestFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
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
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * Application constructor.
     *
     * @param RequestHandlerInterface            $handler
     * @param null|MiddlewareInterface           $middleware
     * @param null|ServerRequestFactoryInterface $serverRequestFactory
     */
    public function __construct(
        RequestHandlerInterface $handler,
        MiddlewareInterface $middleware = null,
        ServerRequestFactoryInterface $serverRequestFactory = null
    ) {
        $client  = new PsrHttpClient($handler, $serverRequestFactory);
        $handler = new PsrHttpClientHandler($client);

        if ($middleware) {
            $handler = new MiddlewareHandler(
                $middleware,
                $handler
            );
        }

        $this->handler = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->handler->sendRequest($request);
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
