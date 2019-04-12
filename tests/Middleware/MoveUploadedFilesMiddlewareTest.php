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

use CoiSA\Http\Middleware\MoveUploadedFilesMiddleware;
use CoiSA\Http\Test\Handler\AbstractMiddlewareTest;

/**
 * Class MoveUploadedFilesMiddlewareTest
 *
 * @package CoiSA\Http\Test\Middleware
 */
final class MoveUploadedFilesMiddlewareTest extends AbstractMiddlewareTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new MoveUploadedFilesMiddleware(\sys_get_temp_dir());
    }

    public function testInvalidPathThrowException(): void
    {
        $this->markTestIncomplete();
    }

    public function testNotWritablePathThrowException(): void
    {
        $this->markTestIncomplete();
    }

    public function testMoveFileWithoutFilter(): void
    {
        $this->markTestIncomplete();
    }

    public function testMoveOnlyFilteredFiles(): void
    {
        $this->markTestIncomplete();
    }
}
