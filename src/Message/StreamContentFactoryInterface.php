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

/**
 * Interface StreamContentFactoryInterface
 *
 * @package CoiSA\Http\Message
 */
interface StreamContentFactoryInterface
{
    /**
     * @param array $data
     *
     * @return string
     */
    public function createStreamContentFromArray(array $data): string;
}
