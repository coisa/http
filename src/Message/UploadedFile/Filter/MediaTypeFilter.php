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
 * Class MediaTypeFilter
 *
 * @package CoiSA\Http\Message\UploadedFile\Filter
 */
final class MediaTypeFilter implements FilterInterface
{
    /**
     * @var string
     */
    private $mediaType;

    /**
     * MediaTypeFilter constructor.
     *
     * @param string $mediaType
     */
    public function __construct(string $mediaType)
    {
        $this->mediaType = $mediaType;
    }

    /**
     * @param UploadedFileInterface ...$uploadedFiles
     *
     * @return array
     */
    public function filter(UploadedFileInterface ...$uploadedFiles): array
    {
        $filtered = [];

        foreach ($uploadedFiles as $uploadedFile) {
            $mediaType = $uploadedFile->getClientMediaType();

            if ($mediaType === $this->mediaType) {
                $filtered[] = $uploadedFile;
            }
        }

        return $filtered;
    }
}
