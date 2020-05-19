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

namespace CoiSA\Http\Message;

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class BaseUriFactory
 *
 * @package CoiSA\Http\Message
 */
final class BaseUriFactory implements UriFactoryInterface
{
    /** @var UriInterface */
    private $baseUrl;

    /** @var UriFactoryInterface */
    private $uriFactory;

    /**
     * BaseUriFactory constructor.
     *
     * @param UriInterface $baseUrl
     * @param UriFactoryInterface $uriFactory
     */
    public function __construct(
        UriInterface $baseUrl,
        UriFactoryInterface $uriFactory
    ) {
        $this->baseUrl    = $baseUrl;
        $this->uriFactory = $uriFactory;
    }

    /**
     * @param string $uri
     *
     * @return UriInterface
     */
    public function createUri(string $uri = ''): UriInterface
    {
        $baseUrl = (string) $this->baseUrl;
        $uri = $baseUrl . '/' . ltrim('/', $uri);

        return $this->uriFactory->createUri($uri);
    }
}
