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

use CoiSA\Http\Handler\MiddlewareHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class MiddlewareHandlerTest
 *
 * @package CoiSA\Http\Test\Handler
 */
final class MiddlewareHandlerTest extends TestCase
{
    /** @var MiddlewareInterface|ObjectProphecy */
    private $middleware;

    /** @var ObjectProphecy|RequestHandlerInterface */
    private $handler;

    /** @var MiddlewareHandler */
    private $middlewareHandler;

    /** @var ObjectProphecy|ServerRequestInterface */
    private $serverRequest;

    /** @var ObjectProphecy|ResponseInterface */
    private $response;

    public function setUp(): void
    {
        $this->middleware        = $this->prophesize(MiddlewareInterface::class);
        $this->handler           = $this->prophesize(RequestHandlerInterface::class);
        $this->serverRequest     = $this->prophesize(ServerRequestInterface::class);
        $this->response          = $this->prophesize(ResponseInterface::class);
        $this->middlewareHandler = new MiddlewareHandler($this->middleware->reveal(), $this->handler->reveal());

        $this->middleware->process($this->serverRequest->reveal(), Argument::type(RequestHandlerInterface::class))->will([$this->response, 'reveal']);
        $this->handler->handle($this->serverRequest->reveal())->will([$this->response, 'reveal']);
    }

    public function testImplementPsrInterfaces(): void
    {
        $this->assertInstanceOf(RequestHandlerInterface::class, $this->middlewareHandler);
        $this->assertInstanceOf(MiddlewareInterface::class, $this->middlewareHandler);
    }

    public function testHandleReturnHandlerResponse(): void
    {
        $response = $this->middlewareHandler->handle($this->serverRequest->reveal());
        $this->assertSame($response, $this->response->reveal());
    }

    public function testHandleExecuteMiddlewareBeforeHandler(): void
    {
        $this->markTestIncomplete();
    }

    public function testProcessReturnMiddlewareHandlerResponse(): void
    {
        $next         = $this->prophesize(RequestHandlerInterface::class);
        $nextResponse = $this->prophesize(ResponseInterface::class);

        $next->handle($this->serverRequest->reveal())->will([$nextResponse, 'reveal']);

        $response = $this->middlewareHandler->process($this->serverRequest->reveal(), $next->reveal());
        $this->assertSame($this->response->reveal(), $response);
        $this->assertNotSame($nextResponse, $response);
    }
}
