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

use CoiSA\Http\Middleware\RouteMiddleware;
use Fig\Http\Message\RequestMethodInterface;

/**
 * Class RouteMiddlewareTest
 *
 * @package CoiSA\Http\Test\Handler
 */
final class RouteMiddlewareTest extends AbstractMiddlewareTest
{
    private $pattern;

    public function setUp(): void
    {
        parent::setUp();

        $this->pattern = \uniqid('test', true);

        $this->middleware = new RouteMiddleware(
            RequestMethodInterface::METHOD_GET,
            $this->pattern,
            $this->handler->reveal()
        );

        $this->serverRequest->getRequestTarget()->willReturn($this->pattern);
        $this->serverRequest->getMethod()->willReturn(RequestMethodInterface::METHOD_GET);
    }

    public function testNotMatchedRouteReturnNextHandlerResponse(): void
    {
        $this->serverRequest->getRequestTarget()->willReturn(\uniqid('fail', true));

        $response = $this->middleware->process(
            $this->serverRequest->reveal(),
            $this->nextHandler->reveal()
        );

        $this->assertSame($this->nextResponse->reveal(), $response);
        $this->assertNotSame($this->response->reveal(), $response);
    }

    public function testMatchedRouteReturnHandlerResponse(): void
    {
        $response = $this->middleware->process(
            $this->serverRequest->reveal(),
            $this->nextHandler->reveal()
        );

        $this->assertSame($this->response->reveal(), $response);
        $this->assertNotSame($this->nextResponse->reveal(), $response);
    }

    public function testMatchedRouteWithInvalidMethodReturnNextResponse(): void
    {
        $this->serverRequest->getMethod()->willReturn(RequestMethodInterface::METHOD_POST);

        $response = $this->middleware->process(
            $this->serverRequest->reveal(),
            $this->nextHandler->reveal()
        );

        $this->assertSame($this->nextResponse->reveal(), $response);
        $this->assertNotSame($this->response->reveal(), $response);
    }
}
