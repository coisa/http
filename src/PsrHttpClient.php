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

use CoiSA\Http\Handler\ErrorHandler;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class PsrHttpClient
 *
 * @package CoiSA\Http
 */
final class PsrHttpClient implements ClientInterface
{
    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * @var RequestHandlerInterface
     */
    private $errorHandler;

    /**
     * @var null|ServerRequestFactoryInterface
     */
    private $serverRequestFactory;

    /**
     * PsrHttpClient constructor.
     *
     * @param RequestHandlerInterface            $defaultHandler
     * @param null|RequestHandlerInterface       $errorHandler
     * @param null|ServerRequestFactoryInterface $serverRequestFactory
     */
    public function __construct(
        RequestHandlerInterface $defaultHandler,
        RequestHandlerInterface $errorHandler = null,
        ServerRequestFactoryInterface $serverRequestFactory = null
    ) {
        $this->handler              = $defaultHandler;
        $this->errorHandler         = $errorHandler;
        $this->serverRequestFactory = $serverRequestFactory ?? new Psr17Factory();
    }

    /**
     * @param RequestInterface $request
     *
     * @throws \Throwable If an exception rise with no error-handler provided
     *
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        if (!$request instanceof ServerRequestInterface) {
            $request = $this->serverRequestFactory->createServerRequest(
                $request->getMethod(),
                $request->getUri(),
                $_SERVER
            );
        }

        try {
            return $this->handler->handle($request);
        } catch (\Throwable $throwable) {
            if (!$this->errorHandler) {
                throw $throwable;
            }

            $handler = new ErrorHandler($throwable, $this->errorHandler);

            return $handler->handle($request);
        }
    }
}
