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

use CoiSA\Http\Middleware\SendHeadersMiddleware;
use CoiSA\Http\Test\Handler\AbstractMiddlewareTest;

/**
 * Class SendHeadersMiddlewareTest
 *
 * @package CoiSA\Http\Test\Middleware
 */
final class SendHeadersMiddlewareTest extends AbstractMiddlewareTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new SendHeadersMiddleware();
    }

    public function testSendExactHandlerResponseHeaders(): void
    {
        $this->markTestIncomplete();
    }

    public function testMiddlewareWorksInAnyDeclarationOrder(): void
    {
        $this->markTestIncomplete();
    }
}
