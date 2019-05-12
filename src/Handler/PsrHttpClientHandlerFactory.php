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

use CoiSA\Http\Handler\PsrHttpClientHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;

/**
 * Class PsrHttpClientHandlerFactory
 *
 * @package CoiSA\Http\Client
 */
final class PsrHttpClientHandlerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return PsrHttpClientHandler
     */
    public function __invoke(ContainerInterface $container): PsrHttpClientHandler
    {
        $client = $container->get(ClientInterface::class);

        return $this->fromPsrClient($client);
    }

    /**
     * @param ClientInterface $client
     *
     * @return PsrHttpClientHandler
     */
    public function fromPsrClient(ClientInterface $client): PsrHttpClientHandler
    {
        return new PsrHttpClientHandler($client);
    }
}
