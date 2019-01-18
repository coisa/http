<?php declare(strict_types=1);
/**
 * @author Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>
 * @package CoiSA\Http\Handler
 */

namespace CoiSA\Http\Handler;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class CurlHandler
 *
 * @package CoiSA\Http\Handler
 */
final class CurlHandler implements RequestHandlerInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * CurlHandler constructor.
     *
     * @param ResponseFactoryInterface|null $responseFactory
     */
    public function __construct(ResponseFactoryInterface $responseFactory = null)
    {
        $this->responseFactory = $responseFactory ?? new Psr17Factory();
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // @TODO make the curl request
        var_dump($request);

        return $this->responseFactory->createResponse();
    }
}
