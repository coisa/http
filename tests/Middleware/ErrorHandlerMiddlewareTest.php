<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Http\Test\Middleware;

use CoiSA\Http\Middleware\ErrorHandlerMiddleware;
use CoiSA\Http\Test\Handler\AbstractMiddlewareTest;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

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

        $this->serverRequest->withAttribute(ErrorHandlerMiddleware::class, Argument::type(\Throwable::class))->will([$this->serverRequest, 'reveal']);
    }

    public function testSuccessExecutionReturnHandlerResponse(): void
    {
        $handler         = $this->prophesize(RequestHandlerInterface::class);
        $handlerResponse = $this->prophesize(ResponseInterface::class);
        $handler->handle($this->serverRequest->reveal())->will([$handlerResponse, 'reveal']);

        $response = $this->middleware->process($this->serverRequest->reveal(), $handler->reveal());
        $this->assertSame($handlerResponse->reveal(), $response);
        $this->assertNotSame($this->response->reveal(), $response);
    }

    public function testOnExceptionReturnErrorHandlerResponse(): void
    {
        $handler         = $this->prophesize(RequestHandlerInterface::class);
        $handlerResponse = $this->prophesize(ResponseInterface::class);
        $handler->handle($this->serverRequest->reveal())->willThrow(\Exception::class);

        $response = $this->middleware->process($this->serverRequest->reveal(), $handler->reveal());
        $this->assertSame($this->response->reveal(), $response);
        $this->assertNotSame($handlerResponse->reveal(), $response);
    }
}
