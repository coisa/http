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
 * Class ExtensionFilter
 *
 * @package CoiSA\Http\Message\UploadedFile
 */
final class ExtensionFilter implements FilterInterface
{
    /**
     * @var string
     */
    private $extension;

    /**
     * ExtensionFilter constructor.
     *
     * @param string $extension
     */
    public function __construct(string $extension)
    {
        $this->extension = \ltrim('.', $extension);
    }

    /**
     * @param UploadedFileInterface ...$uploadedFiles
     *
     * @return UploadedFileInterface[]
     */
    public function filter(UploadedFileInterface ...$uploadedFiles): array
    {
        $filtered = [];

        foreach ($uploadedFiles as $uploadedFile) {
            $extension = \pathinfo(
                $uploadedFile->getClientFilename(),
                PATHINFO_EXTENSION
            );

            if ($extension === $this->extension) {
                $filtered[] = $uploadedFile;
            }
        }

        return $filtered;
    }
}
