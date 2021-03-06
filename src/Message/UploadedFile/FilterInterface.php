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
     * @return \Iterator
     */
    public function filter(UploadedFileInterface ...$uploadedFiles): \Iterator;
}
