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

use CoiSA\Http\Handler\StatusCodeHandler;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class StatusCodeHandlerTest
 *
 * @package CoiSA\Http\Test\Handler
 */
final class StatusCodeHandlerTest extends TestCase
{
    /** @var ObjectProphecy|RequestHandlerInterface */
    private $handler;

    /** @var ObjectProphecy|ServerRequestInterface */
    private $serverRequest;

    /** @var ObjectProphecy|ResponseInterface */
    private $response;

    public function setUp(): void
    {
        $this->handler       = $this->prophesize(RequestHandlerInterface::class);
        $this->serverRequest = $this->prophesize(ServerRequestInterface::class);
        $this->response      = $this->prophesize(ResponseInterface::class);

        $this->handler->handle($this->serverRequest->reveal())->will([$this->response, 'reveal']);
    }

    public function testInvalidStatusThrowsUnexpectedValueException(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        new StatusCodeHandler($this->handler->reveal(), rand(600, 700));
    }

    /**
     * @dataProvider provideStatusCodes
     */
    public function testResponseHaveSameGivenStatusCode($statusCode)
    {
        $this->response->withStatus($statusCode)->will([$this->response, 'reveal']);
        $this->response->getStatusCode()->willReturn($statusCode);

        $handler = new StatusCodeHandler($this->handler->reveal(), $statusCode);
        $response = $handler->handle($this->serverRequest->reveal());

        $this->assertEquals($statusCode, $response->getStatusCode());
    }

    public function provideStatusCodes()
    {
        $reflection    = new \ReflectionClass(StatusCodeInterface::class);
        $allowedStatus = $reflection->getConstants();

        return array_chunk($allowedStatus, 1);
    }
}
