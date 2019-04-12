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

use CoiSA\Http\Handler\GuzzleHandler;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class GuzzleHandlerTest
 *
 * @package CoiSA\Http\Test
 */
final class GuzzleHandlerTest extends TestCase
{
    /** @var ClientInterface|ObjectProphecy */
    private $client;

    /** @var ObjectProphecy|ServerRequestInterface */
    private $serverRequest;

    /** @var ObjectProphecy|ResponseInterface */
    private $response;

    /** @var GuzzleHandler */
    private $handler;

    public function setUp(): void
    {
        $this->client        = $this->prophesize(ClientInterface::class);
        $this->serverRequest = $this->prophesize(ServerRequestInterface::class);
        $this->response      = $this->prophesize(ResponseInterface::class);
        $this->handler       = new GuzzleHandler($this->client->reveal());

        $this->client->send($this->serverRequest->reveal())->will([$this->response, 'reveal']);
    }

    public function testHandlerImplementInterface(): void
    {
        $this->assertInstanceOf(RequestHandlerInterface::class, $this->handler);
    }

    public function testHandleReturnResponseFromClient(): void
    {
        $response = $this->handler->handle($this->serverRequest->reveal());
        $client   = $this->client->reveal();
        $this->assertEquals($client->send($this->serverRequest->reveal()), $response);
    }
}
