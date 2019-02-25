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

use CoiSA\Http\Middleware\RequestMethodMiddleware;
use Fig\Http\Message\RequestMethodInterface;
use Fig\Http\Message\StatusCodeInterface;

/**
 * Class RequestMethodMiddlewareTest
 *
 * @package CoiSA\Http\Test\Handler
 */
final class RequestMethodMiddlewareTest extends AbstractMiddlewareTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new RequestMethodMiddleware(
            RequestMethodInterface::METHOD_GET,
            $this->handler->reveal()
        );

        $this->nextResponse->getStatusCode()->willReturn(StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED);
    }

    public function testInvalidMethodThrowException(): void
    {
        $this->expectException(\UnexpectedValueException::class);

        new RequestMethodMiddleware(
            \uniqid('test', false),
            $this->handler->reveal()
        );
    }

    public function provideMethods()
    {
        $reflection     = new \ReflectionClass(RequestMethodInterface::class);
        $allowedMethods = $reflection->getConstants();

        return \array_chunk($allowedMethods, 1);
    }

    /**
     * @dataProvider provideMethods
     *
     * @param string $method
     */
    public function testGivenMethodReturnHandlerResponse(string $method): void
    {
        $this->serverRequest->getMethod()->willReturn($method);

        $middleware = new RequestMethodMiddleware(
            $method,
            $this->handler->reveal()
        );

        $response = $middleware->process($this->serverRequest->reveal(), $this->nextHandler->reveal());

        $this->assertSame($this->response->reveal(), $response);
        $this->assertNotSame($this->nextResponse->reveal(), $response);
    }

    /**
     * @dataProvider provideMethods
     *
     * @param string $method
     */
    public function testDiffMethodReturnNextResponseWithStatusCodeMethodNotAllowed(string $method): void
    {
        $this->serverRequest->getMethod()->willReturn(
            $method === RequestMethodInterface::METHOD_GET ?
                RequestMethodInterface::METHOD_POST :
                RequestMethodInterface::METHOD_GET
        );

        $middleware = new RequestMethodMiddleware(
            $method,
            $this->handler->reveal()
        );

        $response = $middleware->process($this->serverRequest->reveal(), $this->nextHandler->reveal());

        $this->assertSame($this->nextResponse->reveal(), $response);
        $this->assertNotSame($this->response->reveal(), $response);

        $this->assertEquals(StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED, $response->getStatusCode());
    }
}
