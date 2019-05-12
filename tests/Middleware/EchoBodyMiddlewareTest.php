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
use Prophecy\Argument;
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
    private $stream;

    /** @var bool */
    private $oef = false;

    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new EchoBodyMiddleware();

        $this->content = \uniqid('content', true);

        $this->stream = $this->prophesize(StreamInterface::class);

        $this->stream->isSeekable()->willReturn(false);
        $this->stream->eof()->will([$this, 'getEof']);
        $this->stream->read(Argument::type('integer'))->willReturn($this->content);

        $this->response->getBody()->will([$this->stream, 'reveal']);

        \ob_start();
    }

    public function tearDown(): void
    {
        while (\ob_get_level() > 1) {
            \ob_end_clean();
        }
    }

    public function getEof()
    {
        $oef = $this->oef;
        $this->oef = !$this->oef;

        return $oef;
    }

    public function testProcessWillReturnResponseBodyContent(): void
    {
        $this->stream->isSeekable()->shouldBeCalledOnce();
        $this->stream->eof()->shouldBeCalledTimes(2);
        $this->stream->read(Argument::type('integer'))->shouldBeCalledOnce();

        $this->assertEquals($this->content, $this->processStreamContent());
    }

    public function testProcessWillRewindSeekableStreamBeforeEchoContent(): void
    {
        $this->stream->isSeekable()->willReturn(true);
        $this->stream->rewind()->shouldBeCalledOnce();

        $this->assertEquals($this->content, $this->processStreamContent());
    }

    private function processStreamContent()
    {
        $this->middleware->process($this->serverRequest->reveal(), $this->handler->reveal());

        return \ob_get_clean();
    }
}
