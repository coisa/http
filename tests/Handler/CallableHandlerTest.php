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

use CoiSA\Http\Handler\CallableHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class CallableHandlerTest
 *
 * @package CoiSA\Http\Test
 */
final class CallableHandlerTest extends TestCase
{
    /** @var ObjectProphecy|ServerRequestInterface */
    private $serverRequest;

    /** @var ObjectProphecy|ResponseInterface */
    private $response;

    public function setUp(): void
    {
        $this->serverRequest = $this->prophesize(ServerRequestInterface::class);
        $this->response      = $this->prophesize(ResponseInterface::class);
    }

    public function testInvalidCallbackThrowsTypeError(): void
    {
        $handler = new CallableHandler(function (): void {
        });
        $this->expectException(\TypeError::class);
        $handler->handle($this->serverRequest->reveal());
    }

    public function testHandleReturnsCallbackResponse(): void
    {
        $response = $this->response;
        $callback = function () use ($response) {
            return $response->reveal();
        };
        $handler = new CallableHandler($callback);
        $this->assertEquals($callback($this->serverRequest->reveal()), $handler->handle($this->serverRequest->reveal()));
    }
}
