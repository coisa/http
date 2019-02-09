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

use CoiSA\Http\Client\RequestHandlerClient;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RequestHandlerClientTest
 *
 * @package CoiSA\Http\Test
 */
final class RequestHandlerClientTest extends TestCase
{
    /** @var ObjectProphecy|RequestHandlerInterface */
    private $requestHandler;

    /** @var ObjectProphecy|ServerRequestFactoryInterface */
    private $serverRequestFactory;

    /** @var ObjectProphecy|RequestInterface */
    private $request;

    /** @var ObjectProphecy|ServerRequestInterface */
    private $serverRequest;

    /** @var ObjectProphecy|ResponseInterface */
    private $response;

    public function setUp(): void
    {
        $this->requestHandler       = $this->prophesize(RequestHandlerInterface::class);
        $this->serverRequestFactory = $this->prophesize(ServerRequestFactoryInterface::class);
        $this->request              = $this->prophesize(RequestInterface::class);
        $this->serverRequest        = $this->prophesize(ServerRequestInterface::class);
        $this->response             = $this->prophesize(ResponseInterface::class);

        $method = 'GET';
        $uri    = 'http://google.com';

        $this->request->getMethod()->willReturn($method);
        $this->request->getUri()->willReturn($uri);

        $this->serverRequestFactory->createServerRequest($method, $uri, $_SERVER)->will([$this->serverRequest, 'reveal']);

        $this->requestHandler->handle($this->request->reveal())->will([$this->response, 'reveal']);
        $this->requestHandler->handle($this->serverRequest->reveal())->will([$this->response, 'reveal']);
    }

    public function testImplementClientInterface(): void
    {
        $client = new RequestHandlerClient($this->requestHandler->reveal());
        $this->assertInstanceOf(ClientInterface::class, $client);
    }

    public function testConstructorWithoutServerRequestFactory(): void
    {
        $client = new RequestHandlerClient($this->requestHandler->reveal());
        $this->assertInstanceOf(RequestHandlerClient::class, $client);
    }

    public function testConstructorWithServerRequestFactory(): void
    {
        $client = new RequestHandlerClient($this->requestHandler->reveal(), $this->serverRequestFactory->reveal());
        $this->assertInstanceOf(RequestHandlerClient::class, $client);
    }

    public function testSendServerRequestReturnResponse(): void
    {
        $client   = new RequestHandlerClient($this->requestHandler->reveal());
        $response = $client->sendRequest($this->serverRequest->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testSendRequestReturnResponse(): void
    {
        $client   = new RequestHandlerClient($this->requestHandler->reveal(), $this->serverRequestFactory->reveal());
        $response = $client->sendRequest($this->request->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
