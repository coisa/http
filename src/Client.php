<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Http
 */

namespace CoiSA\Http;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Client
 *
 * @package CoiSA\Http
 */
final class Client implements ClientInterface
{
    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * @var MiddlewareInterface|null
     */
    private $middleware;

    /**
     * @var ServerRequestFactoryInterface|null
     */
    private $serverRequestFactory;

    /**
     * Client constructor.
     *
     * @param RequestHandlerInterface $handler
     * @param MiddlewareInterface|null $middleware
     * @param ServerRequestFactoryInterface|null $serverRequestFactory
     */
    public function __construct(
        RequestHandlerInterface $handler,
        MiddlewareInterface $middleware = null,
        ServerRequestFactoryInterface $serverRequestFactory = null
    ) {
        $this->handler = $handler;
        $this->middleware = $middleware;
        $this->serverRequestFactory = $serverRequestFactory ?? new Psr17Factory();
    }

    /**
     * @param RequestInterface $request
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

        if (!$this->middleware) {
            return $this->handler->handle($request);
        }

        return $this->middleware->process($request, $this->handler);
    }
}
