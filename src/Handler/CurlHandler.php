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
     * @param null|ResponseFactoryInterface $responseFactory
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
        try {
            $resource = \curl_init((string) $request->getUri());
            \curl_exec($resource);
        } catch (\Throwable $throwable) {
        }

        if (isset($resource) && \is_resource($resource)) {
            \curl_close($resource);
        }

        return $this->responseFactory->createResponse();
    }
}
