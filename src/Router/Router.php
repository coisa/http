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

use Psr\Http\Message\RequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Interface RouterInterface
 *
 * @package CoiSA\Http
 */
final class Router implements RouterInterface
{
    /**
     * @var array
     */
    private $routes;

    /**
     * @var RequestHandlerInterface
     */
    private $notFoundHandler;

    /**
     * Router constructor.
     *
     * @param RequestHandlerInterface $notFoundHandler
     */
    public function __construct(RequestHandlerInterface $notFoundHandler)
    {
        $this->notFoundHandler = $notFoundHandler;
    }

    /**
     * @param string $method
     * @param string $regex
     * @param RequestHandlerInterface $requestHandler
     */
    public function addRoute(string $method, string $regex, RequestHandlerInterface $requestHandler): void
    {
        $this->routes[$method][$regex] = $requestHandler;
    }

    /**
     * @param RequestInterface $request
     *
     * @return RouteMatchInterface
     */
    public function match(RequestInterface $request): RouteMatchInterface
    {
        if (!isset($this->routes[$request->getMethod()])) {
            return new RouteMatch($this->notFoundHandler);
        }

        foreach ($this->routes[$request->getMethod()] as $regex => $requestHandler) {
            if (!preg_match($regex, $request->getRequestTarget(), $matches)) {
                continue;
            }

            return new RouteMatch($requestHandler, $matches);
        }
    }
}
