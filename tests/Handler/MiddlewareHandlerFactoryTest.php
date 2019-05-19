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

use CoiSA\Http\Client\MiddlewareHandlerFactory;
use CoiSA\Http\Handler\MiddlewareHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class MiddlewareHandlerFactoryTest
 *
 * @package CoiSA\Http\Test
 */
final class MiddlewareHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var MiddlewareInterface|ObjectProphecy */
    private $middleware;

    /** @var ObjectProphecy|RequestHandlerInterface */
    private $requestHandler;

    /** @var MiddlewareHandlerFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container      = $this->prophesize(ContainerInterface::class);
        $this->middleware     = $this->prophesize(MiddlewareInterface::class);
        $this->requestHandler = $this->prophesize(RequestHandlerInterface::class);

        $this->factory = new MiddlewareHandlerFactory();

        $this->container->get(MiddlewareInterface::class)->shouldBeCalledOnce()->will([$this->middleware, 'reveal']);
        $this->container->get(RequestHandlerInterface::class)->shouldBeCalledOnce()->will([$this->requestHandler, 'reveal']);
    }

    public function testInvokeWithMiddlewareAndRequestHandlerWillReturnMiddlewareHandler(): void
    {
        $client = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(MiddlewareHandler::class, $client);
    }

    public function testInvokeRaiseTypeErrorWhenContainerGetReturnUnexpectedMiddleware(): void
    {
        $this->container->get(MiddlewareInterface::class)->willReturn(\uniqid(__METHOD__, true));

        $this->expectException(\TypeError::class);
        ($this->factory)($this->container->reveal());
    }

    public function testInvokeRaiseTypeErrorWhenContainerGetReturnUnexpectedRequestHandler(): void
    {
        $this->container->get(RequestHandlerInterface::class)->willReturn(\uniqid(__METHOD__, true));

        $this->expectException(\TypeError::class);
        ($this->factory)($this->container->reveal());
    }
}
