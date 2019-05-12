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

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RequestHandlerClientFactory.php
 *
 * @package CoiSA\Http\Client
 */
final class RequestHandlerClientFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return RequestHandlerClient
     */
    public function __invoke(ContainerInterface $container): RequestHandlerClient
    {
        $requestHandler = $container->get(RequestHandlerInterface::class);

        $serverRequestFactory = $container->has(ServerRequestFactoryInterface::class) ?
            $container->get(ServerRequestFactoryInterface::class) : null;

        return $this->fromRequestHandler($requestHandler, $serverRequestFactory);
    }

    /**
     * @param RequestHandlerInterface            $requestHandler
     * @param null|ServerRequestFactoryInterface $serverRequestFactory
     *
     * @return RequestHandlerClient
     */
    public function fromRequestHandler(
        RequestHandlerInterface $requestHandler,
        ServerRequestFactoryInterface $serverRequestFactory = null
    ): RequestHandlerClient {
        return new RequestHandlerClient($requestHandler, $serverRequestFactory);
    }
}
