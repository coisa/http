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

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class StatusCodeMiddleware
 *
 * @package CoiSA\Http\Middleware
 */
final class StatusCodeMiddleware implements MiddlewareInterface
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * StatusCodeMiddleware constructor.
     *
     * @param int $statusCode
     *
     * @throws \ReflectionException
     */
    public function __construct(int $statusCode)
    {
        $reflection    = new \ReflectionClass(StatusCodeInterface::class);
        $allowedStatus = $reflection->getConstants();

        if (!\in_array($statusCode, $allowedStatus)) {
            throw new \UnexpectedValueException('Invalid HTTP Status Code');
        }

        $this->statusCode = $statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request)->withStatus($this->statusCode);
    }
}
