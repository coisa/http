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

use CoiSA\Http\Handler\PsrHttpClientHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class PsrHttpClientHandlerTest
 *
 * @package CoiSA\Http\Test
 */
final class PsrHttpClientHandlerTest extends TestCase
{
    /** @var ClientInterface|ObjectProphecy */
    private $client;

    /** @var ObjectProphecy|RequestInterface */
    private $request;

    /** @var ObjectProphecy|ServerRequestInterface */
    private $serverRequest;

    /** @var ObjectProphecy|ResponseInterface */
    private $response;

    /** @var PsrHttpClientHandler */
    private $handler;

    public function setUp(): void
    {
        $this->client        = $this->prophesize(ClientInterface::class);
        $this->request       = $this->prophesize(RequestInterface::class);
        $this->serverRequest = $this->prophesize(ServerRequestInterface::class);
        $this->response      = $this->prophesize(ResponseInterface::class);
        $this->handler       = new PsrHttpClientHandler($this->client->reveal());

        $this->client->sendRequest($this->request->reveal())->will([$this->response, 'reveal']);
        $this->client->sendRequest($this->serverRequest->reveal())->will([$this->response, 'reveal']);
    }

    public function testHandlerImplementInterfaces(): void
    {
        $this->assertInstanceOf(ClientInterface::class, $this->handler);
        $this->assertInstanceOf(RequestHandlerInterface::class, $this->handler);
    }

    public function testHandleReturnResponseFromClient(): void
    {
        $response = $this->handler->handle($this->serverRequest->reveal());
        $client   = $this->client->reveal();
        $this->assertEquals($client->sendRequest($this->serverRequest->reveal()), $response);
    }

    public function testSendRequestReturnResponseFromClient(): void
    {
        $response = $this->handler->sendRequest($this->request->reveal());
        $client   = $this->client->reveal();
        $this->assertEquals($client->sendRequest($this->request->reveal()), $response);

        $response = $this->handler->sendRequest($this->serverRequest->reveal());
        $this->assertEquals($client->sendRequest($this->serverRequest->reveal()), $response);
    }
}
