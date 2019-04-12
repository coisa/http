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

namespace CoiSA\Http\Middleware;

use CoiSA\Http\Handler\MiddlewareHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RouteMiddleware
 *
 * @package CoiSA\Http\Middleware
 */
final class RouteMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @var MiddlewareInterface
     */
    private $middleware;

    /**
     * RouteMiddleware constructor.
     *
     * @param string                  $method
     * @param string                  $pattern
     * @param RequestHandlerInterface $handler
     */
    public function __construct(string $method, string $pattern, RequestHandlerInterface $handler)
    {
        $this->pattern    = $pattern;
        $this->middleware = new RequestMethodMiddleware($method, $handler);
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestHandler = new MiddlewareHandler($this->middleware, $handler);
        $middleware     = new PregMatchRequestTargetMiddleware($this->pattern, $requestHandler);

        return $middleware->process($request, $handler);
    }
}
