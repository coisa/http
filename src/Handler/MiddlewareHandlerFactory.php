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

namespace CoiSA\Http\Client;

use CoiSA\Http\Handler\MiddlewareHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class HttpPlugHandlerFactory
 *
 * @package CoiSA\Http\Client
 */
final class MiddlewareHandlerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return MiddlewareHandler
     */
    public function __invoke(ContainerInterface $container): MiddlewareHandler
    {
        return $this->plugMiddlewareAndHandler(
            $container->get(MiddlewareInterface::class),
            $container->get(RequestHandlerInterface::class)
        );
    }

    /**
     * @param MiddlewareInterface     $middleware
     * @param RequestHandlerInterface $requestHandler
     *
     * @return MiddlewareHandler
     */
    public function plugMiddlewareAndHandler(
        MiddlewareInterface $middleware,
        RequestHandlerInterface $requestHandler
    ): MiddlewareHandler {
        return new MiddlewareHandler($middleware, $requestHandler);
    }
}
