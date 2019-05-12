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

namespace CoiSA\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class EchoBodyMiddleware
 *
 * @package CoiSA\Http\Middleware
 */
final class EchoBodyMiddleware implements MiddlewareInterface
{
    /**
     * @const string
     */
    const DEFAULT_BUFFER_SIZE = 1024 * 8;

    /**
     * @var int
     */
    private $bufferSize;

    /**
     * EchoBodyMiddleware constructor.
     *
     * @param int $bufferSize
     */
    public function __construct(int $bufferSize = self::DEFAULT_BUFFER_SIZE)
    {
        $this->bufferSize = $bufferSize;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $stream   = $response->getBody();

        if ($stream->isSeekable()) {
            $stream->rewind();
        }

        while (!$stream->eof()) {
            echo $stream->read($this->bufferSize);
        }

        return $response;
    }
}
