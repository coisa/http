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

use CoiSA\Http\Message\ServerRequestFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RequestHandlerClient
 *
 * @package CoiSA\Http\Client
 */
final class RequestHandlerClient implements ClientInterface
{
    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * @var ServerRequestFactoryInterface
     */
    private $serverRequestFactory;

    /**
     * RequestHandlerClient constructor.
     *
     * @param RequestHandlerInterface            $defaultHandler
     * @param null|ServerRequestFactoryInterface $serverRequestFactory
     */
    public function __construct(
        RequestHandlerInterface $defaultHandler,
        ServerRequestFactoryInterface $serverRequestFactory = null
    ) {
        $this->handler              = $defaultHandler;
        $this->serverRequestFactory = $serverRequestFactory ?? new ServerRequestFactory();
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

        return $this->handler->handle($request);
    }
}
