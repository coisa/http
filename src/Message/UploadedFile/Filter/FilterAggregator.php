<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Http\Message\UploadedFile\Filter;

use CoiSA\Http\Message\UploadedFile\FilterInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Class FilterAggregator
 *
 * @package CoiSA\Http\Message\UploadedFile\Filter
 */
final class FilterAggregator implements FilterInterface
{
    /**
     * @var FilterInterface[]
     */
    private $filters;

    /**
     * FilterAggregator constructor.
     *
     * @param FilterInterface ...$filters
     */
    public function __construct(FilterInterface ...$filters)
    {
        $this->filters = $filters;
    }

    /**
     * @param UploadedFileInterface ...$uploadedFiles
     *
     * @return UploadedFileInterface[]
     */
    public function filter(UploadedFileInterface ...$uploadedFiles): array
    {
        $filtered = clone $uploadedFiles;

        foreach ($this->filters as $filter) {
            if (empty($filtered)) {
                break;
            }

            $filtered = $filter->filter($filtered);
        }

        return $filtered;
    }
}
