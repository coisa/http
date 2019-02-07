<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Http\Handler;

use CoiSA\Http\Middleware\RequestMethodMiddleware;
use CoiSA\Http\Middleware\PregMatchRequestTargetMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RouterHandler
 *
 * @package CoiSA\Http\Handler
 */
final class RouterHandler implements RequestHandlerInterface
{
    /**
     * @var RequestHandlerInterface
     */
    private $notFoundHandler;

    /**
     * @var MiddlewareInterface[]
     */
    private $routes;

    /**
     * RouterHandler constructor.
     *
     * @param RequestHandlerInterface $notFoundHandler
     */
    public function __construct(RequestHandlerInterface $notFoundHandler)
    {
        $this->notFoundHandler = $notFoundHandler;
    }

    /**
     * @param string                  $method
     * @param string                  $regex
     * @param RequestHandlerInterface $requestHandler
     */
    public function addRoute(string $method, string $regex, RequestHandlerInterface $requestHandler): void
    {
        $middleware     = new RequestMethodMiddleware($method, $requestHandler);
        $this->routes[] = new PregMatchRequestTargetMiddleware(
            $regex,
            new MiddlewareHandler($middleware, $this)
        );
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
        $middleware = \current($this->routes);

        if (!$middleware) {
            \reset($this->routes);

            return $this->notFoundHandler->handle($request);
        }

        \next($this->routes);

        return $middleware->process($request, $this);
    }
}
