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

use CoiSA\Http\Handler\RouterHandler;
use CoiSA\Http\Middleware\PregMatchRequestTargetMiddleware;
use Fig\Http\Message\RequestMethodInterface;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RouterHandlerTest
 *
 * @package CoiSA\Http\Test\Handler
 */
final class RouterHandlerTest extends TestCase
{
    /** @var RouterHandler */
    private $handler;

    /** @var ObjectProphecy|RequestHandlerInterface */
    private $notFoundHandler;

    /** @var ObjectProphecy|RequestHandlerInterface */
    private $routeHandler;

    /** @var ObjectProphecy|ServerRequestInterface */
    private $serverRequest;

    /** @var ObjectProphecy|ResponseInterface */
    private $response;

    public function setUp(): void
    {
        $this->notFoundHandler = $this->prophesize(RequestHandlerInterface::class);
        $this->routeHandler    = $this->prophesize(RequestHandlerInterface::class);
        $this->serverRequest   = $this->prophesize(ServerRequestInterface::class);
        $this->response        = $this->prophesize(ResponseInterface::class);
        $this->handler         = new RouterHandler($this->notFoundHandler->reveal());

        $routeResponse = $this->prophesize(ResponseInterface::class);

        $this->notFoundHandler->handle($this->serverRequest->reveal())->will([$this->response, 'reveal']);
        $this->response->withStatus(StatusCodeInterface::STATUS_NOT_FOUND)->will([$this->response, 'reveal']);

        $this->routeHandler->handle($this->serverRequest->reveal())->will([$routeResponse, 'reveal']);

        $this->serverRequest->getMethod()->willReturn(RequestMethodInterface::METHOD_GET);
        $this->serverRequest->getRequestTarget()->willReturn('/users/(?<id>\d+)');
        $this->serverRequest->withAttribute(PregMatchRequestTargetMiddleware::class, Argument::type('array'))->will([$this->serverRequest, 'reveal']);
    }

    public function testEmptyRouterReturnNotFoundHandlerResponse(): void
    {
        $response = $this->handler->handle($this->serverRequest->reveal());
        $this->assertSame($this->response->reveal(), $response);
    }

    public function testNotMatchedRouteReturnNotFoundHandlerResponse(): void
    {
        $this->handler->addRoute(
            RequestMethodInterface::METHOD_GET,
            \uniqid('test', true),
            $this->routeHandler->reveal()
        );

        $response = $this->handler->handle($this->serverRequest->reveal());
        $this->assertSame($this->response->reveal(), $response);
    }

    public function testMatchedRouteReturnNewResponse(): void
    {
        $this->markTestIncomplete();

        $notFoundResponse = $this->response->reveal();

        $this->handler->addRoute(
            RequestMethodInterface::METHOD_GET,
            '/users/1',
            $this->routeHandler->reveal()
        );

        $response = $this->handler->handle($this->serverRequest->reveal());
        $this->assertNotSame($notFoundResponse, $response);
    }
}
