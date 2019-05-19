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

namespace CoiSA\Http\Client;

use CoiSA\Http\Message\UploadedFile\Filter\ExtensionFilter;
use CoiSA\Http\Message\UploadedFile\Filter\FilterAggregator;
use CoiSA\Http\Message\UploadedFile\Filter\MediaTypeFilter;
use CoiSA\Http\Message\UploadedFile\FilterInterface;
use Psr\Container\ContainerInterface;

/**
 * Class FilterFactory
 *
 * @package CoiSA\Http\Client
 */
final class FilterFactory
{
    /**
     * @const array
     */
    const DEFAULT_FILTERS = [
        ExtensionFilter::class,
        MediaTypeFilter::class
    ];

    /**
     * @param ContainerInterface $container
     *
     * @return FilterInterface
     */
    public function __invoke(ContainerInterface $container): FilterInterface
    {
        if ($container->has(FilterAggregator::class)) {
            return $container->get(FilterAggregator::class);
        }

        $filters = [];
        foreach (self::DEFAULT_FILTERS as $filter) {
            if (!$container->has($filter)) {
                continue;
            }

            $filters[] = $container->get($filter);
        }

        return $this->fromFilters(...$filters);
    }

    /**
     * @param FilterInterface ...$filters
     *
     * @return FilterAggregator
     */
    public function fromFilters(FilterInterface ...$filters): FilterAggregator
    {
        return new FilterAggregator(...$filters);
    }
}
