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

use Fig\Http\Message\RequestMethodInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class AbstractMiddlewareTest
 *
 * @package CoiSA\Http\Test\Handler
 */
abstract class AbstractMiddlewareTest extends TestCase
{
    /** @var ObjectProphecy|RequestHandlerInterface */
    protected $handler;

    /** @var ObjectProphecy|RequestHandlerInterface */
    protected $nextHandler;

    /** @var ObjectProphecy|ServerRequestInterface */
    protected $serverRequest;

    /** @var ObjectProphecy|ResponseInterface */
    protected $response;

    /** @var ObjectProphecy|ResponseInterface */
    protected $nextResponse;

    /** @var MiddlewareInterface */
    protected $middleware;

    public function setUp(): void
    {
        $this->handler       = $this->prophesize(RequestHandlerInterface::class);
        $this->nextHandler   = $this->prophesize(RequestHandlerInterface::class);
        $this->serverRequest = $this->prophesize(ServerRequestInterface::class);
        $this->response      = $this->prophesize(ResponseInterface::class);
        $this->nextResponse  = $this->prophesize(ResponseInterface::class);

        $this->handler->handle($this->serverRequest->reveal())->will([$this->response, 'reveal']);
        $this->nextHandler->handle($this->serverRequest->reveal())->will([$this->nextResponse, 'reveal']);

        $this->response->withStatus(Argument::type('int'))->will([$this->response, 'reveal']);
        $this->nextResponse->withStatus(Argument::type('int'))->will([$this->nextResponse, 'reveal']);

        $this->response->getHeaders()->will([$this->response, 'reveal']);
        $this->nextResponse->getHeaders()->will([$this->nextResponse, 'reveal']);

        $this->response->hasHeader(Argument::type('string'))->willReturn(true);
        $this->nextResponse->hasHeader(Argument::type('string'))->willReturn(true);

        $this->serverRequest->getQueryParams()->willReturn([]);
        $this->serverRequest->getUploadedFiles()->willReturn([]);
        $this->serverRequest->getMethod()->willReturn(RequestMethodInterface::METHOD_GET);
        $this->serverRequest->withAttribute(Argument::type('string'), Argument::any())->will([$this->serverRequest, 'reveal']);
    }

    public function testImplementsPsrServerMiddleware(): void
    {
        $this->assertInstanceOf(MiddlewareInterface::class, $this->middleware);
    }

    public function testProcessMethodReturnResponse(): void
    {
        $response = $this->middleware->process($this->serverRequest->reveal(), $this->handler->reveal());
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
