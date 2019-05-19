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

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ErrorHandlerMiddleware
 *
 * @package CoiSA\Http\Middleware
 */
final class ErrorHandlerMiddleware implements MiddlewareInterface
{
    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * ErrorHandlerMiddleware constructor.
     *
     * @param RequestHandlerInterface  $handler
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(RequestHandlerInterface $handler, EventDispatcherInterface $eventDispatcher)
    {
        $this->handler         = $handler;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Throwable $throwable) {
            $errorRequest = $request->withAttribute(
                self::class,
                $throwable
            );

            $this->eventDispatcher->dispatch($throwable);

            return $this->handler->handle($errorRequest);
        }
    }
}
