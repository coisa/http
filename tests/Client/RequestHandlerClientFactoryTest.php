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

namespace CoiSA\Http\Test;

use CoiSA\Http\Client\RequestHandlerClient;
use CoiSA\Http\Client\RequestHandlerClientFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RequestHandlerClientFactoryTest
 *
 * @package CoiSA\Http\Test
 */
final class RequestHandlerClientFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var ObjectProphecy|RequestHandlerInterface */
    private $requestHandler;

    /** @var ObjectProphecy|ServerRequestFactoryInterface */
    private $serverRequestFactory;

    /** @var RequestHandlerClientFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container            = $this->prophesize(ContainerInterface::class);
        $this->requestHandler       = $this->prophesize(RequestHandlerInterface::class);
        $this->serverRequestFactory = $this->prophesize(ServerRequestFactoryInterface::class);

        $this->factory = new RequestHandlerClientFactory();

        $this->container->has(ServerRequestFactoryInterface::class)->shouldBeCalledOnce()->willReturn(true);

        $this->container->get(RequestHandlerInterface::class)->shouldBeCalledOnce()->will([$this->requestHandler, 'reveal']);
        $this->container->get(ServerRequestFactoryInterface::class)->shouldBeCalledOnce()->will([$this->serverRequestFactory, 'reveal']);
    }

    public function testInvokeWithRequestHandlerWithoutServerRequestFactoryReturnRequestHandlerClient(): void
    {
        $this->container->has(ServerRequestFactoryInterface::class)->willReturn(false);

        $this->container->get(ServerRequestFactoryInterface::class)->shouldNotBeCalled();
        $this->container->get(RequestHandlerInterface::class)->will([$this->requestHandler, 'reveal']);

        $client = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(RequestHandlerClient::class, $client);
    }

    public function testInvokeWithRequestHandlerAndServerRequestFactoryReturnRequestHandlerClient(): void
    {
        $this->container->get(RequestHandlerInterface::class)->will([$this->requestHandler, 'reveal']);
        $this->container->get(ServerRequestFactoryInterface::class)->will([$this->serverRequestFactory, 'reveal']);

        $client = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(RequestHandlerClient::class, $client);
    }

    public function testInvokeRaiseTypeErrorWhenContainerGetReturnUnexpectedRequestHandler(): void
    {
        $this->container->get(RequestHandlerInterface::class)->willReturn(\uniqid(__METHOD__, true));

        $this->expectException(\TypeError::class);

        ($this->factory)($this->container->reveal());
    }

    public function testInvokeRaiseTypeErrorWhenContainerGetReturnUnexpectedServerRequestFactory(): void
    {
        $this->container->get(ServerRequestFactoryInterface::class)->willReturn(\uniqid(__METHOD__, true));

        $this->expectException(\TypeError::class);

        ($this->factory)($this->container->reveal());
    }
}
