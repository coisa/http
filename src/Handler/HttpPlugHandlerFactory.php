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

namespace CoiSA\Http\Client;

use CoiSA\Http\Handler\GuzzleHandler;
use CoiSA\Http\Handler\HttpPlugHandler;
use Http\Client\HttpClient;
use Psr\Container\ContainerInterface;

/**
 * Class HttpPlugHandlerFactory
 *
 * @package CoiSA\Http\Client
 */
final class HttpPlugHandlerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return HttpPlugHandler
     */
    public function __invoke(ContainerInterface $container): HttpPlugHandler
    {
        $client = $container->get(HttpClient::class);

        return $this->fromHttpPlugClient($client);
    }

    /**
     * @param HttpClient $client
     *
     * @return GuzzleHandler
     */
    public function fromHttpPlugClient(HttpClient $client): HttpPlugHandler
    {
        return new HttpPlugHandler($client);
    }
}
