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

use Psr\Http\Message\RequestInterface;

/**
 * Interface RouterInterface
 *
 * @package CoiSA\Http
 */
interface RouterInterface
{
    /**
     * @param RequestInterface $request
     *
     * @return RouteMatchInterface
     */
    public function match(RequestInterface $request): RouteMatchInterface;
}
