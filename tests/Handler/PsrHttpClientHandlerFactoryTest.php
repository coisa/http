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

use CoiSA\Http\Client\PsrHttpClientHandlerFactory;
use CoiSA\Http\Handler\PsrHttpClientHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;

/**
 * Class PsrHttpClientHandlerFactoryTest
 *
 * @package CoiSA\Http\Test
 */
final class PsrHttpClientHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var ClientInterface|ObjectProphecy */
    private $client;

    /** @var PsrHttpClientHandlerFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->client    = $this->prophesize(ClientInterface::class);

        $this->factory = new PsrHttpClientHandlerFactory();

        $this->container->get(ClientInterface::class)->shouldBeCalledOnce()->will([$this->client, 'reveal']);
    }

    public function testInvokeWithPsrClientWillReturnPsrClientHandler(): void
    {
        $client = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(PsrHttpClientHandler::class, $client);
    }

    public function testInvokeRaiseTypeErrorWhenContainerGetReturnUnexpectedPsrClient(): void
    {
        $this->container->get(ClientInterface::class)->willReturn(\uniqid(__METHOD__, true));

        $this->expectException(\TypeError::class);

        ($this->factory)($this->container->reveal());
    }
}
