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

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class StatusCodeHandler
 *
 * @package CoiSA\Http\Handler
 */
final class StatusCodeHandler implements RequestHandlerInterface
{
    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * StatusCodeHandler constructor.
     *
     * @param RequestHandlerInterface $requestHandler
     * @param int                     $statusCode
     *
     * @throws \ReflectionException
     */
    public function __construct(
        RequestHandlerInterface $requestHandler,
        int $statusCode
    ) {
        $this->handler    = $requestHandler;
        $this->statusCode = $statusCode;

        $reflection    = new \ReflectionClass(StatusCodeInterface::class);
        $allowedStatus = $reflection->getConstants();

        if (!\in_array($this->statusCode, $allowedStatus)) {
            throw new \UnexpectedValueException('Invalid HTTP Status Code');
        }
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->handler->handle($request)
            ->withStatus($this->statusCode);
    }
}
