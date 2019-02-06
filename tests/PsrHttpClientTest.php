<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

use CoiSA\Http\PsrHttpClient;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 */
final class PsrHttpClientTest extends TestCase
{
    /** @var RequestHandlerInterface|ObjectProphecy */
    private $requestHandler;

    /** @var ServerRequestFactoryInterface|ObjectProphecy */
    private $serverRequestFactory;

    /** @var RequestInterface|ObjectProphecy */
    private $request;

    /** @var ServerRequestInterface|ObjectProphecy */
    private $serverRequest;

    /** @var ResponseInterface|ObjectProphecy */
    private $response;

    public function setUp(): void
    {
        $this->requestHandler = $this->prophesize(RequestHandlerInterface::class);
        $this->serverRequestFactory = $this->prophesize(ServerRequestFactoryInterface::class);
        $this->request = $this->prophesize(RequestInterface::class);
        $this->serverRequest = $this->prophesize(ServerRequestInterface::class);
        $this->response = $this->prophesize(ResponseInterface::class);

        $method = 'GET';
        $uri = 'http://google.com';

        $this->request->getMethod()->willReturn($method);
        $this->request->getUri()->willReturn($uri);

        $this->serverRequestFactory->createServerRequest($method, $uri, $_SERVER)->will([$this->serverRequest, 'reveal']);

        $this->requestHandler->handle($this->request->reveal())->will([$this->response, 'reveal']);
        $this->requestHandler->handle($this->serverRequest->reveal())->will([$this->response, 'reveal']);
    }

    public function testImplementClientInterface()
    {
        $client = new PsrHttpClient($this->requestHandler->reveal());
        $this->assertInstanceOf(ClientInterface::class, $client);
    }

    public function testConstructorWithoutServerRequestFactory()
    {
        $client = new PsrHttpClient($this->requestHandler->reveal());
        $this->assertInstanceOf(PsrHttpClient::class, $client);
    }

    public function testConstructorWithServerRequestFactory()
    {
        $client = new PsrHttpClient($this->requestHandler->reveal(), $this->serverRequestFactory->reveal());
        $this->assertInstanceOf(PsrHttpClient::class, $client);
    }

    public function testSendServerRequestReturnResponse()
    {
        $client = new PsrHttpClient($this->requestHandler->reveal());
        $response = $client->sendRequest($this->serverRequest->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testSendRequestReturnResponse()
    {
        $client = new PsrHttpClient($this->requestHandler->reveal(), $this->serverRequestFactory->reveal());
        $response = $client->sendRequest($this->request->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
