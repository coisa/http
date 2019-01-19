<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Http\Handler;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class PsrHttpClientHandler
 *
 * @package CoiSA\Http\Handler
 */
final class PsrHttpClientHandler implements RequestHandlerInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * PsrHttpClientHandler constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->client->sendRequest($request);
    }
}
