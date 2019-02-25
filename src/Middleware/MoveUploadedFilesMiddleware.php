<?php declare(strict_types=1);
/*
 * This file is part of coisa/http.
 *
 * (c) Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CoiSA\Http\Middleware;

use CoiSA\Http\Message\UploadedFile\FilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class MoveUploadedFilesMiddleware
 *
 * @package CoiSA\Http\Middleware
 */
final class MoveUploadedFilesMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private $targetPath;

    /**
     * @var null|FilterInterface
     */
    private $filter;

    /**
     * MoveUploadedFilesMiddleware constructor.
     *
     * @param string               $targetPath
     * @param null|FilterInterface $filter
     */
    public function __construct(string $targetPath, FilterInterface $filter = null)
    {
        $this->targetPath = $targetPath;
        $this->filter     = $filter;

        if (!\is_dir($this->targetPath)) {
            throw new \UnexpectedValueException(\sprintf(
                'The given target path `%s` is not a directory',
                $this->targetPath
            ));
        }

        if (!\is_writable($this->targetPath)) {
            throw new \RuntimeException(\sprintf(
                'The target directory `%s` does not exists or is not writable',
                $this->targetPath
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var UploadedFileInterface[] $uploadedFiles */
        $uploadedFiles = $request->getUploadedFiles();

        if ($this->filter) {
            $uploadedFiles = $this->filter->filter(...$uploadedFiles);
        }

        if (!empty($uploadedFiles)) {
            foreach ($uploadedFiles as $uploadedFile) {
                $uploadedFile->moveTo($this->targetPath);
            }
        }

        return $handler->handle($request);
    }
}
