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

use CoiSA\Http\Client\GuzzleHandlerFactory;
use CoiSA\Http\Handler\GuzzleHandler;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class GuzzleHandlerFactoryTest
 *
 * @package CoiSA\Http\Test
 */
final class GuzzleHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var ClientInterface|ObjectProphecy */
    private $client;

    /** @var GuzzleHandlerFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->client    = $this->prophesize(ClientInterface::class);

        $this->factory = new GuzzleHandlerFactory();

        $this->container->get(ClientInterface::class)->shouldBeCalledOnce()->will([$this->client, 'reveal']);
    }

    public function testInvokeWithGuzzleClientWillReturnGuzzleHandler(): void
    {
        $client = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(GuzzleHandler::class, $client);
    }

    public function testInvokeRaiseTypeErrorWhenContainerGetReturnUnexpectedGuzzleClient(): void
    {
        $this->container->get(ClientInterface::class)->willReturn(\uniqid(__METHOD__, true));

        $this->expectException(\TypeError::class);

        ($this->factory)($this->container->reveal());
    }
}
