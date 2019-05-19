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
use CoiSA\Http\Client\HttpPlugHandlerFactory;
use CoiSA\Http\Handler\HttpPlugHandler;
use Http\Client\HttpClient;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

/**
 * Class HttpPlugHandlerFactoryTest
 *
 * @package CoiSA\Http\Test
 */
final class HttpPlugHandlerFactoryTest extends TestCase
{
    /** @var ContainerInterface|ObjectProphecy */
    private $container;

    /** @var ObjectProphecy|HttpClient */
    private $client;

    /** @var GuzzleHandlerFactory */
    private $factory;

    public function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->client    = $this->prophesize(HttpClient::class);

        $this->factory = new HttpPlugHandlerFactory();

        $this->container->get(HttpClient::class)->shouldBeCalledOnce()->will([$this->client, 'reveal']);
    }

    public function testFactoryWithHttpClientWillReturnHttpPlugHandler(): void
    {
        $client = ($this->factory)($this->container->reveal());

        $this->assertInstanceOf(HttpPlugHandler::class, $client);
    }

    public function testFactoryRaiseTypeErrorWhenContainerGetReturnUnexpectedObject(): void
    {
        $this->container->get(HttpClient::class)->willReturn(\uniqid(__METHOD__, true));

        $this->expectException(\TypeError::class);

        ($this->factory)($this->container->reveal());
    }
}
