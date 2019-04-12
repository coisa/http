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

use CoiSA\Http\Middleware\RequestHandlerMiddleware;
use CoiSA\Http\Test\Handler\AbstractMiddlewareTest;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RequestHandlerMiddlewareTest
 *
 * @package CoiSA\Http\Test
 */
final class RequestHandlerMiddlewareTest extends AbstractMiddlewareTest
{
    /** @var array */
    private $execution = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new RequestHandlerMiddleware($this->handler->reveal());

        $testClass = $this;
        $this->handler->handle($this->serverRequest->reveal())->will(function () use ($testClass) {
            $testClass->execution[] = \spl_object_hash($this);

            return $testClass->response->reveal();
        });
        $this->nextHandler->handle($this->serverRequest->reveal())->will(function () use ($testClass) {
            $testClass->execution[] = \spl_object_hash($this);

            return $testClass->nextResponse->reveal();
        });
    }

    public function testImplementPsrServerRequestHandler(): void
    {
        $this->assertInstanceOf(RequestHandlerInterface::class, $this->middleware);
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
            \spl_object_hash($this->nextHandler),
        ];
        $this->middleware->process($this->serverRequest->reveal(), $this->nextHandler->reveal());
        $this->assertEquals($expected, $this->execution);
    }

    public function testProcessReturnResponseFromDependencyHandler(): void
    {
        $serverRequest  = $this->serverRequest->reveal();
        $response       = $this->middleware->process($serverRequest, $this->nextHandler->reveal());
        $requestHandler = $this->handler->reveal();
        $this->assertEquals($requestHandler->handle($serverRequest), $response);
    }
}
