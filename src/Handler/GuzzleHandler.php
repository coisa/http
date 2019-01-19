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

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class GuzzleHandler
 *
 * @package CoiSA\Http\Handler
 */
final class GuzzleHandler implements RequestHandlerInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * GuzzleHandler constructor.
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->client->send($request);
    }
}
