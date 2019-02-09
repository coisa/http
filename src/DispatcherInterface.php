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

use Psr\Http\Client\ClientInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Interface ApplicationInterface
 *
 * @package CoiSA\Http
 */
interface DispatcherInterface extends ClientInterface, MiddlewareInterface, RequestHandlerInterface
{
}
