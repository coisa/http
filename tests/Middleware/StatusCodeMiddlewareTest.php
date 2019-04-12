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

namespace CoiSA\Http\Test\Handler;

use CoiSA\Http\Middleware\StatusCodeMiddleware;
use Fig\Http\Message\StatusCodeInterface;

/**
 * Class StatusCodeMiddlewareTest
 *
 * @package CoiSA\Http\Test\Handler
 */
final class StatusCodeMiddlewareTest extends AbstractMiddlewareTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new StatusCodeMiddleware(StatusCodeInterface::STATUS_OK);
    }

    public function testInvalidStatusThrowsUnexpectedValueException(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        $middleware = new StatusCodeMiddleware(\rand(600, 700));
        $middleware->process($this->serverRequest->reveal(), $this->handler->reveal());
    }

    /**
     * @dataProvider provideStatusCodes
     *
     * @param int $statusCode
     */
    public function testResponseHaveSameGivenStatusCode(int $statusCode): void
    {
        $this->response->getStatusCode()->willReturn($statusCode);

        $middleware  = new StatusCodeMiddleware($statusCode);
        $response    = $middleware->process($this->serverRequest->reveal(), $this->handler->reveal());

        $this->assertEquals($statusCode, $response->getStatusCode());
    }

    public function provideStatusCodes()
    {
        $reflection    = new \ReflectionClass(StatusCodeInterface::class);
        $allowedStatus = $reflection->getConstants();

        return \array_chunk($allowedStatus, 1);
    }
}
