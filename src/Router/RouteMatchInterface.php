<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Http;

use Psr\Http\Server\RequestHandlerInterface;

/**
 * Interface RouteMatchInterface
 *
 * @package CoiSA\Http
 */
interface RouteMatchInterface
{
    /**
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * @return RequestHandlerInterface
     */
    public function getHandler(): RequestHandlerInterface;

    /**
     * @return array
     */
    public function getVariables(): array;
}
