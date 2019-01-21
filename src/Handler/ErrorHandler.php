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
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ErrorHandler
 *
 * @package CoiSA\Http\Handler
 */
final class ErrorHandler implements RequestHandlerInterface
{
    /**
     * @var \Throwable
     */
    private $throwable;

    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * ErrorHandler constructor.
     *
     * @param \Throwable              $throwable
     * @param RequestHandlerInterface $handler
     */
    public function __construct(
        \Throwable $throwable,
        RequestHandlerInterface $handler
    ) {
        $this->throwable = $throwable;
        $this->handler   = $handler;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->handler->handle(
            $request->withAttribute(
                self::class,
                $this->throwable
            )
        );
    }
}
