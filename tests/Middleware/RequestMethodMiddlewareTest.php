<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Http\Test\Handler;

use CoiSA\Http\Middleware\RequestMethodMiddleware;
use Fig\Http\Message\RequestMethodInterface;

/**
 * Class RequestMethodMiddlewareTest
 *
 * @package CoiSA\Http\Test\Handler
 */
final class RequestMethodMiddlewareTest extends AbstractMiddlewareTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new RequestMethodMiddleware(
            RequestMethodInterface::METHOD_GET,
            $this->handler->reveal()
        );
    }
}
