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
use GuzzleHttp\ClientInterface;
use Psr\Container\ContainerInterface;

/**
 * Class GuzzleHandlerFactory
 *
 * @package CoiSA\Http\Client
 */
final class GuzzleHandlerFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return GuzzleHandler
     */
    public function __invoke(ContainerInterface $container): GuzzleHandler
    {
        $client = $container->get(ClientInterface::class);

        return $this->fromGuzzleClient($client);
    }

    /**
     * @param ClientInterface $client
     *
     * @return GuzzleHandler
     */
    public function fromGuzzleClient(ClientInterface $client): GuzzleHandler
    {
        return new GuzzleHandler($client);
    }
}
