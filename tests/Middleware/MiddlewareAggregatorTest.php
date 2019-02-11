<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Http\Test\Middleware;

use CoiSA\Http\Middleware\MiddlewareAggregator;
use CoiSA\Http\Test\Handler\AbstractMiddlewareTest;

/**
 * Class MiddlewareAggregatorTest
 *
 * @package CoiSA\Http\Test\Middleware
 */
final class MiddlewareAggregatorTest extends AbstractMiddlewareTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new MiddlewareAggregator();
    }

    public function testEmptyMiddlewareReturnHandlerResponse(): void
    {
        $response = $this->middleware->process($this->serverRequest->reveal(), $this->nextHandler->reveal());
        $this->assertSame($this->nextResponse->reveal(), $response);
    }

    public function testMiddlewareExecuteAsFifo(): void
    {
        $this->markTestIncomplete();
    }

    public function testMiddlewareCanStopNextExecutions(): void
    {
        $this->markTestIncomplete();
    }
}
