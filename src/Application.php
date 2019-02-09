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
use CoiSA\Http\Middleware\ErrorHandlerMiddleware;
use CoiSA\Http\Middleware\RequestHandlerMiddleware;
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
     * @var DispatcherInterface
     */
    private $dispatcher;

    /**
     * @var ErrorHandlerMiddleware
     */
    private $errorHandler;

    /**
     * Application constructor.
     *
     * @param DispatcherInterface $dispatcher
     * @param RequestHandlerInterface $errorHandler
     */
    public function __construct(
        DispatcherInterface $dispatcher,
        RequestHandlerInterface $errorHandler
    ) {
        $this->dispatcher = $dispatcher;
        $this->errorHandler = new ErrorHandlerMiddleware($errorHandler);
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->dispatcher->sendRequest($request);
    }

    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->errorHandler->process($request, $this->dispatcher);
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $handler = new MiddlewareHandler(
            $this->dispatcher,
            $handler
        );

        return $this->errorHandler->process($request, $handler);
    }
}
