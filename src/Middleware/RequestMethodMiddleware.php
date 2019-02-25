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

use Fig\Http\Message\RequestMethodInterface;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RequestMethodMiddleware
 *
 * @package CoiSA\Http\Middleware
 */
final class RequestMethodMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * @var MiddlewareInterface
     */
    private $middleware;

    /**
     * RequestMethodMiddleware constructor.
     *
     * @param string                  $method
     * @param RequestHandlerInterface $handler
     */
    public function __construct(string $method, RequestHandlerInterface $handler)
    {
        $constant = RequestMethodInterface::class . '::METHOD_' . \strtoupper($method);

        if (false === \defined($constant)) {
            throw new \UnexpectedValueException('Invalid HTTP method');
        }

        $this->method     = \constant($constant);
        $this->handler    = $handler;
        $this->middleware = new StatusCodeMiddleware(StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED);
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->method !== $request->getMethod()) {
            return $this->middleware->process($request, $handler);
        }

        return $this->handler->handle($request);
    }
}
