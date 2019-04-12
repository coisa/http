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

namespace CoiSA\Http\Test\Middleware;

use CoiSA\Http\Middleware\ErrorHandlerMiddleware;
use CoiSA\Http\Test\Handler\AbstractMiddlewareTest;
use Prophecy\Argument;

/**
 * Class ErrorHandlerMiddlewareTest
 *
 * @package CoiSA\Http\Test\Middleware
 */
final class ErrorHandlerMiddlewareTest extends AbstractMiddlewareTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new ErrorHandlerMiddleware($this->handler->reveal());

        $this->serverRequest->withAttribute(Argument::type('string'), Argument::type(\Throwable::class))->will([$this->serverRequest, 'reveal']);
    }

    public function testSuccessExecutionReturnHandlerResponse(): void
    {
        $response = $this->middleware->process($this->serverRequest->reveal(), $this->nextHandler->reveal());
        $this->assertSame($this->nextResponse->reveal(), $response);
        $this->assertNotSame($this->response->reveal(), $response);
    }

    public function testOnExceptionReturnErrorHandlerResponse(): void
    {
        $this->nextHandler->handle($this->serverRequest->reveal())->willThrow(\Exception::class);

        $response = $this->middleware->process($this->serverRequest->reveal(), $this->nextHandler->reveal());
        $this->assertSame($this->response->reveal(), $response);
        $this->assertNotSame($this->nextResponse->reveal(), $response);
    }
}
