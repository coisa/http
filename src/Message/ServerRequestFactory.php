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

use Psr\Http\Message\ServerRequestFactoryInterface;

/**
 * Class ServerRequestFactory
 *
 * @package CoiSA\Http\Handler
 */
final class ServerRequestFactory extends \Zend\Diactoros\ServerRequestFactory implements ServerRequestFactoryInterface
{
}
