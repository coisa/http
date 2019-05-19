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

namespace CoiSA\Http\Test\Message\UploadedFile\Filter;

use CoiSA\Http\Message\UploadedFile\Filter\ExtensionFilter;
use CoiSA\Http\Message\UploadedFile\FilterInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\UploadedFileInterface;

final class ExtensionFilterTest extends TestCase
{
    /** @var string Valid extension for tests */
    private $extension;

    /** @var ObjectProphecy|UploadedFileInterface */
    private $uploadedFile;

    /** @var ExtensionFilter */
    private $filter;

    public function setUp(): void
    {
        $this->extension    = \uniqid('ext', false);
        $this->uploadedFile = $this->prophesize(UploadedFileInterface::class);

        $this->uploadedFile->getClientFilename()->willReturn(
            \uniqid('filename', false) . '.' . $this->extension
        );

        $this->filter = new ExtensionFilter($this->extension);
    }

    public function testFilterImplementsFilterInterface(): void
    {
        $this->assertInstanceOf(FilterInterface::class, $this->filter);
    }

    public function testFilterWithOnlyAcceptableUploadedFilesWillReturnSameGivenFiles(): void
    {
        $input = [
            $this->uploadedFile->reveal(),
            $this->uploadedFile->reveal(),
            $this->uploadedFile->reveal(),
        ];

        $generator = $this->filter->filter(...$input);
        $this->assertIsIterable($generator);

        $filtered = \iterator_to_array($generator);

        $this->assertEquals($input, $filtered);
    }

    public function testFilterWillRemoveNotAcceptableExtensions(): void
    {
        $uploadedFile = $this->prophesize(UploadedFileInterface::class);
        $uploadedFile->getClientFilename()->willReturn(
            \uniqid('filename', false) . '.invalid'
        );

        $input = [
            $this->uploadedFile->reveal(),
            $uploadedFile->reveal(),
            $uploadedFile->reveal(),
            $this->uploadedFile->reveal(),
        ];

        $filtered = \iterator_to_array(
            $this->filter->filter(...$input)
        );

        $this->assertNotSameSize($input, $filtered);
        $this->assertEquals([
            $this->uploadedFile->reveal(),
            $this->uploadedFile->reveal(),
        ], $filtered);
    }
}
