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

use Http\Client\HttpClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class HttpPlugHandler
 *
 * @package CoiSA\Http\Handler
 */
final class HttpPlugHandler implements RequestHandlerInterface
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * HttpPlugHandler constructor.
     *
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @throws \Http\Client\Exception
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->client->sendRequest($request);
    }
}
