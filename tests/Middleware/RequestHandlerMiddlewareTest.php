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

use CoiSA\Http\Middleware\RequestHandlerMiddleware;
use CoiSA\Http\Test\Handler\AbstractMiddlewareTest;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RequestHandlerMiddlewareTest
 *
 * @package CoiSA\Http\Test
 */
final class RequestHandlerMiddlewareTest extends AbstractMiddlewareTest
{
    /** @var ObjectProphecy|RequestHandlerInterface */
    private $next;

    /** @var array */
    private $execution = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new RequestHandlerMiddleware($this->handler->reveal());

        $this->next       = $this->prophesize(RequestHandlerInterface::class);

        $testClass = $this;
        $callback  = function () use ($testClass) {
            $testClass->execution[] = \spl_object_hash($this);

            return $testClass->response->reveal();
        };

        $this->handler->handle($this->serverRequest->reveal())->will($callback);
        $this->next->handle($this->serverRequest->reveal())->will($callback);
    }

    public function testImplementPsrInterfaces(): void
    {
        $this->assertInstanceOf(RequestHandlerInterface::class, $this->middleware);
        $this->assertInstanceOf(MiddlewareInterface::class, $this->middleware);
    }

    public function testHandleReturnHandlerResponse(): void
    {
        $serverRequest  = $this->serverRequest->reveal();
        $response       = $this->middleware->handle($serverRequest);
        $requestHandler = $this->handler->reveal();
        $this->assertEquals($requestHandler->handle($serverRequest), $response);
    }

    public function testProcessExecuteBothHandlersInOrder(): void
    {
        $expected = [
            \spl_object_hash($this->handler),
            \spl_object_hash($this->next),
        ];
        $this->middleware->process($this->serverRequest->reveal(), $this->next->reveal());
        $this->assertEquals($expected, $this->execution);
    }

    public function testProcessReturnResponseFromDependencyHandler(): void
    {
        $serverRequest  = $this->serverRequest->reveal();
        $response       = $this->middleware->process($serverRequest, $this->next->reveal());
        $requestHandler = $this->handler->reveal();
        $this->assertEquals($requestHandler->handle($serverRequest), $response);
    }
}
