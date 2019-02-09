<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Http\Test\Handler;

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
    }

    public function testProcessMethodReturnResponse(): void
    {
        $response = $this->middleware->process($this->serverRequest->reveal(), $this->handler->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame($this->response->reveal(), $response);
    }
}
