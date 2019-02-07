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

use CoiSA\Http\Middleware\EchoBodyMiddleware;
use CoiSA\Http\Middleware\ErrorHandlerMiddleware;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ErrorHandlerMiddlewareTest
 *
 * @package CoiSA\Http\Test\Middleware
 */
final class ErrorHandlerMiddlewareTest extends TestCase
{
    /** @var EchoBodyMiddleware */
    private $middleware;

    /** @var ObjectProphecy|RequestHandlerInterface */
    private $requestHandler;

    /** @var ObjectProphecy|ServerRequestInterface */
    private $serverRequest;

    /** @var ObjectProphecy|ResponseInterface */
    private $response;

    public function setUp(): void
    {
        $this->requestHandler = $this->prophesize(RequestHandlerInterface::class);
        $this->serverRequest  = $this->prophesize(ServerRequestInterface::class);
        $this->response       = $this->prophesize(ResponseInterface::class);
        $this->middleware     = new ErrorHandlerMiddleware($this->requestHandler->reveal());

        $this->requestHandler->handle($this->serverRequest->reveal())->will([$this->response, 'reveal']);
        $this->serverRequest->withAttribute(ErrorHandlerMiddleware::class, Argument::type(\Throwable::class))->will([$this->serverRequest, 'reveal']);
    }

    public function testSuccessExecutionReturnHandlerResponse()
    {
        $handler = $this->prophesize(RequestHandlerInterface::class);
        $handlerResponse = $this->prophesize(ResponseInterface::class);
        $handler->handle($this->serverRequest->reveal())->will([$handlerResponse, 'reveal']);

        $response = $this->middleware->process($this->serverRequest->reveal(), $handler->reveal());
        $this->assertSame($handlerResponse->reveal(), $response);
        $this->assertNotSame($this->response->reveal(), $response);
    }

    public function testOnExceptionReturnErrorHandlerResponse()
    {
        $handler = $this->prophesize(RequestHandlerInterface::class);
        $handlerResponse = $this->prophesize(ResponseInterface::class);
        $handler->handle($this->serverRequest->reveal())->willThrow(\Exception::class);

        $response = $this->middleware->process($this->serverRequest->reveal(), $handler->reveal());
        $this->assertSame($this->response->reveal(), $response);
        $this->assertNotSame($handlerResponse->reveal(), $response);
    }
}
