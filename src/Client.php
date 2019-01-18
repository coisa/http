<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Http
 */

namespace CoiSA\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
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
     * Client constructor.
     *
     * @param RequestHandlerInterface $handler
     * @param MiddlewareInterface|null $middleware
     */
    public function __construct(RequestHandlerInterface $handler, MiddlewareInterface $middleware = null)
    {
        $this->handler = $handler;
        $this->middleware = $middleware;
    }

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        if (!$request instanceof ServerRequestInterface) {
            // @TODO transform request into a ServerRequestInterface
        }

        if (!$this->middleware) {
            return $this->handler->handle($request);
        }

        return $this->middleware->process($request, $this->handler);
    }
}
