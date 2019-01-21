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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class MiddlewareHandler
 *
 * @package CoiSA\Http\Handler
 */
final class MiddlewareHandler implements RequestHandlerInterface
{
    /**
     * @var MiddlewareInterface
     */
    private $middleware;

    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * MiddlewareHandler constructor.
     *
     * @param MiddlewareInterface     $middleware
     * @param RequestHandlerInterface $defaultHandler
     */
    public function __construct(
        MiddlewareInterface $middleware,
        RequestHandlerInterface $defaultHandler
    ) {
        $this->middleware = $middleware;
        $this->handler    = $defaultHandler;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middleware->process($request, $this->handler);
    }
}
