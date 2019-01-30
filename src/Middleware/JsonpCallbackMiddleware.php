<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Http\Middleware;

use CoiSA\Http\Message\StreamFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class JsonpCallbackMiddleware
 *
 * @package CoiSA\Http\Middleware
 */
final class JsonpCallbackMiddleware implements MiddlewareInterface
{
    /**
     * @const string
     */
    const REQUEST_QUERY_PARAM = 'callback';

    /**
     * @const string
     */
    const REQUEST_ACCEPT_HEADER = 'text/javascript';

    /**
     * @var string
     */
    private $queryParam;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * JsonpCallbackMiddleware constructor.
     *
     * @param string $queryParam
     * @param StreamFactoryInterface|null $streamFactory
     */
    public function __construct(string $queryParam = self::REQUEST_QUERY_PARAM, StreamFactoryInterface $streamFactory = null)
    {
        $this->queryParam    = $queryParam;
        $this->streamFactory = $streamFactory ?? new StreamFactory();
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $callback = $request->getQueryParams()[$this->queryParam] ?? false;

        if (!$callback) {
            return $response;
        }

        if ($request->hasHeader('Accept')
            && !in_array(self::REQUEST_ACCEPT_HEADER, $request->getHeader('Accept'))
        ) {
            return $response;
        }

        // @TODO accept all types of json content-type ['application/json', 'text/json', 'application/x-json'];

        if ($response->hasHeader('Content-Type')
            && !in_array('application/json', $response->getHeader('Content-Type'))
        ) {
            return $response;
        }

        $content = \htmlspecialchars($callback) . '(' . $response->getBody() . ');';
        $body    = $this->streamFactory->createStream($content);

        return $response
            ->withHeader('Content-Type', 'text/javascript')
            ->withBody($body);
    }
}
