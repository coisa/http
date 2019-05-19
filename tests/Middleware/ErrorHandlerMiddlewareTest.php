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
use Phly\EventDispatcher\EventDispatcher;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class ErrorHandlerMiddlewareTest
 *
 * @package CoiSA\Http\Test\Middleware
 */
final class ErrorHandlerMiddlewareTest extends AbstractMiddlewareTest
{
    /** @var EventDispatcher|ObjectProphecy */
    private $eventDispatcher;

    public function setUp(): void
    {
        parent::setUp();

        $this->eventDispatcher = $this->prophesize(EventDispatcherInterface::class);

        $this->serverRequest->withAttribute(Argument::type('string'), Argument::type(\Throwable::class))->will([$this->serverRequest, 'reveal']);

        $this->middleware = new ErrorHandlerMiddleware(
            $this->handler->reveal(),
            $this->eventDispatcher->reveal()
        );
    }

    public function testProcessWillReturnHandlerResponse(): void
    {
        $response = $this->middleware->process($this->serverRequest->reveal(), $this->nextHandler->reveal());

        $this->assertSame($this->nextResponse->reveal(), $response);
        $this->assertNotSame($this->response->reveal(), $response);
    }

    public function testProcessWillReturnErrorHandlerResponseWhenHandlerRaisesException(): void
    {
        $throwable = new \Exception(
            \uniqid('test', true),
            \random_int(1, 500)
        );

        $this->eventDispatcher->dispatch($throwable)->shouldBeCalledOnce();
        $this->nextHandler->handle($this->serverRequest->reveal())->willThrow($throwable);

        $response = $this->middleware->process($this->serverRequest->reveal(), $this->nextHandler->reveal());

        $this->assertSame($this->response->reveal(), $response);
        $this->assertNotSame($this->nextResponse->reveal(), $response);
    }
}
