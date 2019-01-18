<?php

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Http\Middleware
 */

namespace CoiSA\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class MiddlewareAggregator
 *
 * @package CoiSA\Http\Middleware
 */
final class MiddlewareAggregator implements MiddlewareInterface, RequestHandlerInterface
{
    /**
     * @var \SplQueue<MiddlewareInterface>
     */
    private $middlewares;

    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * MiddlewareQueue constructor.
     *
     * @param MiddlewareInterface ...$middlewares
     */
    public function __construct(MiddlewareInterface ...$middlewares)
    {
        $this->middlewares = new \SplQueue();

        foreach ($middlewares as $middleware) {
            $this->middlewares->push($middleware);
        }
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->middlewares->isEmpty()) {
            return $this->handler->handle($request);
        }

        return $this->process($request, $this);
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->handler) {
            $this->handler = $handler;
        }

        $middleware = $this->middlewares->dequeue();

        return $middleware->process($request, $this);
    }
}
