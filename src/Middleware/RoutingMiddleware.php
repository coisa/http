<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Http\Middleware;

use CoiSA\Http\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RoutingMiddleware
 *
 * @package CoiSA\Http\Middleware
 */
final class RoutingMiddleware implements MiddlewareInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * RoutingMiddleware constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeMatch = $this->router->match($request);

        if ($routeMatch->isSuccess()) {
            foreach ($routeMatch->getVariables() as $name => $value) {
                $request = $request->withAttribute($name, $value);
            }

            return $routeMatch->getHandler()->handle($request);
        }

        return $handler->handle($request);
    }
}
