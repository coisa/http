<?php declare(strict_types=1);
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Http\Handler
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
     * PsrClientHandler constructor.
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
     * @return ResponseInterface
     * @throws \Http\Client\Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->client->sendRequest($request);
    }
}
