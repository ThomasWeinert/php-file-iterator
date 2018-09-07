<?php

declare(strict_types=1);
/*
 * This file is part of php-file-iterator.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\FileIterator\Filter;

use PHPUnit\Framework\TestCase;

/**
 * @covers \SebastianBergmann\FileIterator\Filter\ExcludePaths
 */
final class ExcludePathsTest extends TestCase
{
    public function testIterateFiltersElementsWhichAreNotFiles(): void
    {
        $filePaths = [
            __DIR__ . '/../_fixture/Foo.php',
            __DIR__ . '/../_fixture/Bar.php',
        ];

        $files = \array_map(function (string $path): \SplFileInfo {
            return new \SplFileInfo($path);
        }, $filePaths);

        $iterator = new \ArrayIterator(\array_merge($files, [
            'foo',
        ]));

        $filter = new ExcludePaths($iterator);

        $iterated = \iterator_to_array($filter);

        $this->assertContainsOnlyInstancesOf(\SplFileInfo::class, $iterated);
        $this->assertCount(\count($filePaths), $iterated);
    }

    public function testIterateReturnsAllElementsWhenExcludePathsAreEmpty(): void
    {
        $filePaths = [
            __DIR__ . '/../_fixture/Foo.php',
            __DIR__ . '/../_fixture/Bar.php',
        ];

        $files = \array_map(function (string $path): \SplFileInfo {
            return new \SplFileInfo($path);
        }, $filePaths);

        $iterator = new \ArrayIterator($files);

        $filter = new ExcludePaths($iterator);

        $iterated = \iterator_to_array($filter);

        $this->assertContainsOnlyInstancesOf(\SplFileInfo::class, $iterated);
        $this->assertCount(\count($filePaths), $iterated);
    }

    public function testIterateFiltersElementsWhichAreInExcludedPath(): void
    {
        $filePaths = [
            __DIR__ . '/../_fixture/Foo.php',
            __DIR__ . '/../_fixture/Bar.php',
        ];

        $excludedFilePaths = [
            __DIR__ . '/../_fixture/Foo/Bar.php',
        ];

        $files = \array_map(function (string $path): \SplFileInfo {
            return new \SplFileInfo($path);
        }, \array_merge($filePaths, $excludedFilePaths));

        $iterator = new \ArrayIterator($files);

        $filter = new ExcludePaths(
            $iterator,
            __DIR__ . '/../_fixture/Foo'
        );

        $iterated = \iterator_to_array($filter);

        $this->assertContainsOnlyInstancesOf(\SplFileInfo::class, $iterated);
        $this->assertCount(\count($filePaths), $iterated);
    }

    public function testIterateFiltersElementsWhichAreInExcludedPaths(): void
    {
        $filePaths = [
            __DIR__ . '/../_fixture/Foo.php',
            __DIR__ . '/../_fixture/Bar.php',
        ];

        $excludedFilePaths = [
            __DIR__ . '/../_fixture/Foo/Bar.php',
            __DIR__ . '/../_fixture/Bar/Baz.php',
        ];

        $files = \array_map(function (string $path): \SplFileInfo {
            return new \SplFileInfo($path);
        }, \array_merge($filePaths, $excludedFilePaths));

        $iterator = new \ArrayIterator($files);

        $filter = new ExcludePaths(
            $iterator,
            [
                __DIR__ . '/../_fixture/Foo',
                __DIR__ . '/../_fixture/Bar',
            ]
        );

        $iterated = \iterator_to_array($filter);

        $this->assertContainsOnlyInstancesOf(\SplFileInfo::class, $iterated);
        $this->assertCount(\count($filePaths), $iterated);
    }
}
