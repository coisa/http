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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class PregMatchRequestTargetMiddleware
 *
 * @package CoiSA\Http\Middleware
 */
final class PregMatchRequestTargetMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * PregMatchRequestTargetMiddleware constructor.
     *
     * @param string                  $pattern
     * @param RequestHandlerInterface $handler
     */
    public function __construct(string $pattern, RequestHandlerInterface $handler)
    {
        $this->pattern = '(' . $pattern . ')i';
        $this->handler = $handler;
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!\preg_match($this->pattern, $request->getRequestTarget(), $matches)) {
            return $handler->handle($request);
        }

        return $this->handler->handle($request->withAttribute(self::class, $matches));
    }
}