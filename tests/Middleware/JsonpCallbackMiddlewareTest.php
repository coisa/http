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

use CoiSA\Http\Middleware\JsonpCallbackMiddleware;
use CoiSA\Http\Test\Handler\AbstractMiddlewareTest;

/**
 * Class JsonpCallbackMiddlewareTest
 *
 * @package CoiSA\Http\Test\Middleware
 */
final class JsonpCallbackMiddlewareTest extends AbstractMiddlewareTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new JsonpCallbackMiddleware();
    }
}
