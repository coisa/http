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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class SendHeadersMiddleware
 *
 * @package CoiSA\Http\Middleware
 */
final class SendHeadersMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        \ob_start();
        $response = $handler->handle($request);

        foreach ($response->getHeaders() as $header => $values) {
            $name = $this->normalize($header);

            foreach ($values as $value) {
                \header(
                    \sprintf('%s: %s', $name, $value),
                    true,
                    $response->getStatusCode()
                );
            }
        }

        return $response;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function normalize(string $name): string
    {
        $name = \str_replace('-', ' ', $name);
        $name = \ucwords($name);

        return \str_replace(' ', '-', $name);
    }
}
