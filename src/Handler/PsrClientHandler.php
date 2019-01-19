<?php declare(strict_types=1);
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Http\Handler
 */

namespace CoiSA\Http\Handler;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class PsrClientHandler
 *
 * @package CoiSA\Http\Handler
 */
final class PsrClientHandler implements RequestHandlerInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * PsrClientHandler constructor.
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
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->client->sendRequest($request);
    }
}
