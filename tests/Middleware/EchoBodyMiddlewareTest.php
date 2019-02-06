<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

use CoiSA\Http\Middleware\EchoBodyMiddleware;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class EchoBodyMiddlewareTest
 */
final class EchoBodyMiddlewareTest extends TestCase
{
    /** @var EchoBodyMiddleware */
    private $middleware;

    /** @var ObjectProphecy|RequestHandlerInterface */
    private $requestHandler;

    /** @var ObjectProphecy|ServerRequestInterface */
    private $serverRequest;

    /** @var ObjectProphecy|ResponseInterface */
    private $response;

    /** @var string */
    private $content;

    /** @var ObjectProphecy|StreamInterface */
    private $body;

    public function setUp(): void
    {
        $this->middleware     = new EchoBodyMiddleware();
        $this->requestHandler = $this->prophesize(RequestHandlerInterface::class);
        $this->serverRequest  = $this->prophesize(ServerRequestInterface::class);
        $this->response       = $this->prophesize(ResponseInterface::class);
        $this->body           = $this->prophesize(StreamInterface::class);

        $this->requestHandler->handle($this->serverRequest->reveal())->will([$this->response, 'reveal']);

        $this->content = \uniqid('content', true);
        $this->response->getBody()->will([$this->body, 'reveal']);
        $this->body->getContents()->willReturn($this->content);

        \ob_start();
    }

    public function tearDown(): void
    {
        while (\ob_get_level() > 1) {
            \ob_end_clean();
        }
    }

    public function testImplementsPsrServerMiddleware(): void
    {
        $this->assertInstanceOf(MiddlewareInterface::class, $this->middleware);
    }

    public function testProcessReturnResponse(): void
    {
        $response = $this->middleware->process($this->serverRequest->reveal(), $this->requestHandler->reveal());
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testProcessEchoResponseBodyContent(): void
    {
        $this->middleware->process($this->serverRequest->reveal(), $this->requestHandler->reveal());
        $content = \ob_get_clean();
        $this->assertEquals($this->content, $content);
    }
}
