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

namespace CoiSA\Http\Test\Middleware;

use CoiSA\Http\Middleware\EchoBodyMiddleware;
use CoiSA\Http\Test\Handler\AbstractMiddlewareTest;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\StreamInterface;

/**
 * Class EchoBodyMiddlewareTest
 *
 * @package CoiSA\Http\Test
 */
final class EchoBodyMiddlewareTest extends AbstractMiddlewareTest
{
    /** @var string */
    private $content;

    /** @var ObjectProphecy|StreamInterface */
    private $body;

    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new EchoBodyMiddleware();

        $this->body = $this->prophesize(StreamInterface::class);

        $this->content = \uniqid('content', true);
        $this->response->getBody()->will([$this->body, 'reveal']);
        $this->body->getContents()->willReturn($this->content);

        \ob_start();
    }

    public function tearDown(): void
    {
        while (\ob_get_level() > 1) {
            \ob_end_clean();
        }
    }

    public function testProcessEchoResponseBodyContent(): void
    {
        $this->middleware->process($this->serverRequest->reveal(), $this->handler->reveal());
        $content = \ob_get_clean();
        $this->assertEquals($this->content, $content);
    }
}
