<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Http;

use Psr\Http\Server\RequestHandlerInterface;

/**
 * Interface RouterInterface
 *
 * @package CoiSA\Http
 */
final class RouteMatch implements RouteMatchInterface
{
    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * @var array|null
     */
    private $matches;

    /**
     * Router constructor.
     *
     * @param RequestHandlerInterface $notFoundHandler
     */
    public function __construct(RequestHandlerInterface $handler, ?array $matches = null)
    {
        $this->handler = $handler;
        $this->matches = $matches;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return !empty($this->matches);
    }

    /**
     * @return RequestHandlerInterface
     */
    public function getHandler(): RequestHandlerInterface
    {
        return $this->handler;
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return is_array($this->matches) ? $this->matches : [];
    }
}
