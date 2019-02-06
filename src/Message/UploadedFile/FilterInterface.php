<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Http\Message\UploadedFile;

use Psr\Http\Message\UploadedFileInterface;

/**
 * Interface FilterInterface
 *
 * @package CoiSA\Http\Message\UploadedFile
 */
interface FilterInterface
{
    /**
     * @param UploadedFileInterface ...$uploadedFiles
     *
     * @return UploadedFileInterface[]
     */
    public function filter(UploadedFileInterface ...$uploadedFiles): array;
}
