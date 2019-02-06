<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Http\Test;

use CoiSA\Http\Middleware\EchoBodyMiddleware;
use CoiSA\Http\Middleware\RequestHandlerMiddleware;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RequestHandlerMiddlewareTest
 *
 * @package CoiSA\Http\Test
 */
final class RequestHandlerMiddlewareTest extends TestCase
{
    /** @var EchoBodyMiddleware */
    private $middleware;

    /** @var ObjectProphecy|RequestHandlerInterface */
    private $requestHandler;

    /** @var ObjectProphecy|RequestHandlerInterface */
    private $next;

    /** @var ObjectProphecy|ServerRequestInterface */
    private $serverRequest;

    /** @var ObjectProphecy|ResponseInterface */
    private $response;

    /** @var array */
    private $execution = [];

    public function setUp(): void
    {
        $this->requestHandler = $this->prophesize(RequestHandlerInterface::class);
        $this->next           = $this->prophesize(RequestHandlerInterface::class);
        $this->serverRequest  = $this->prophesize(ServerRequestInterface::class);
        $this->response       = $this->prophesize(ResponseInterface::class);

        $this->middleware     = new RequestHandlerMiddleware($this->requestHandler->reveal());

        $testClass = $this;
        $callback  = function () use ($testClass) {
            $testClass->execution[] = \spl_object_hash($this);

            return $testClass->response->reveal();
        };

        $this->requestHandler->handle($this->serverRequest->reveal())->will($callback);
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
        $requestHandler = $this->requestHandler->reveal();
        $this->assertEquals($requestHandler->handle($serverRequest), $response);
    }

    public function testProcessExecuteBothHandlersInOrder(): void
    {
        $expected = [
            \spl_object_hash($this->requestHandler),
            \spl_object_hash($this->next),
        ];
        $this->middleware->process($this->serverRequest->reveal(), $this->next->reveal());
        $this->assertEquals($expected, $this->execution);
    }

    public function testProcessReturnResponseFromDependencyHandler(): void
    {
        $serverRequest  = $this->serverRequest->reveal();
        $response       = $this->middleware->process($serverRequest, $this->next->reveal());
        $requestHandler = $this->requestHandler->reveal();
        $this->assertEquals($requestHandler->handle($serverRequest), $response);
    }
}
