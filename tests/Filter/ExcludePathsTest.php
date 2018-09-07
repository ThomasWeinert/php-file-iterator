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
 *
 * @uses \SebastianBergmann\FileIterator\WildcardPaths
 */
final class ExcludePathsTest extends TestCase
{
    public function testIterateFiltersElementsWhichAreNotFiles(): void
    {
        $expectedFilePaths = [
            \realpath(__DIR__ . '/../_fixture/Foo.php'),
            \realpath(__DIR__ . '/../_fixture/Bar.php'),
        ];

        $files = \array_map(function (string $path): \SplFileInfo {
            return new \SplFileInfo($path);
        }, $expectedFilePaths);

        $iterator = new \ArrayIterator(\array_merge($files, [
            'foo',
        ]));

        $filter = new ExcludePaths($iterator);

        $iterated = \iterator_to_array($filter);

        $this->assertContainsOnlyInstancesOf(\SplFileInfo::class, $iterated);
        $this->assertEquals($expectedFilePaths, $iterated);
    }

    public function testIterateReturnsAllElementsWhenExcludePathsAreEmpty(): void
    {
        $expectedFilePaths = [
            \realpath(__DIR__ . '/../_fixture/Foo.php'),
            \realpath(__DIR__ . '/../_fixture/Bar.php'),
        ];

        $files = \array_map(function (string $path): \SplFileInfo {
            return new \SplFileInfo($path);
        }, $expectedFilePaths);

        $iterator = new \ArrayIterator($files);

        $filter = new ExcludePaths($iterator);

        $iterated = \iterator_to_array($filter);

        $this->assertContainsOnlyInstancesOf(\SplFileInfo::class, $iterated);
        $this->assertEquals($expectedFilePaths, $iterated);
    }

    public function testIterateFiltersElementsWhichAreInExcludedPath(): void
    {
        $expectedFilePaths = [
            \realpath(__DIR__ . '/../_fixture/Foo.php'),
            \realpath(__DIR__ . '/../_fixture/Bar.php'),
        ];

        $excludedFilePaths = [
            __DIR__ . '/../_fixture/Foo/Bar.php',
        ];

        $files = \array_map(function (string $path): \SplFileInfo {
            return new \SplFileInfo($path);
        }, \array_merge($expectedFilePaths, $excludedFilePaths));

        $iterator = new \ArrayIterator($files);

        $filter = new ExcludePaths(
            $iterator,
            __DIR__ . '/../_fixture/Foo'
        );

        $iterated = \iterator_to_array($filter);

        $this->assertContainsOnlyInstancesOf(\SplFileInfo::class, $iterated);
        $this->assertEquals($expectedFilePaths, $iterated);
    }

    public function testIterateFiltersElementsWhichAreInExcludedPaths(): void
    {
        $expectedFilePaths = [
            \realpath(__DIR__ . '/../_fixture/Foo.php'),
            \realpath(__DIR__ . '/../_fixture/Bar.php'),
        ];

        $excludedFilePaths = [
            __DIR__ . '/../_fixture/Foo/Bar.php',
            __DIR__ . '/../_fixture/Bar/Baz.php',
        ];

        $files = \array_map(function (string $path): \SplFileInfo {
            return new \SplFileInfo($path);
        }, \array_merge($expectedFilePaths, $excludedFilePaths));

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
        $this->assertEquals($expectedFilePaths, $iterated);
    }

    public function testIterateFiltersElementsWhichAreInOrEqualExcludedPaths(): void
    {
        $expectedFilePaths = [
            \realpath(__DIR__ . '/../_fixture/Foo/Bar.php'),
            \realpath(__DIR__ . '/../_fixture/Bar.php'),
        ];

        $excludedFilePaths = [
            __DIR__ . '/../_fixture/Foo.php',
            __DIR__ . '/../_fixture/Bar/Baz.php',
        ];

        $files = \array_map(function (string $path): \SplFileInfo {
            return new \SplFileInfo($path);
        }, \array_merge($expectedFilePaths, $excludedFilePaths));

        $iterator = new \ArrayIterator($files);

        $filter = new ExcludePaths(
            $iterator,
            [
                __DIR__ . '/../_fixture/Foo.php',
                __DIR__ . '/../_fixture/Bar',
            ]
        );

        $iterated = \iterator_to_array($filter);

        $this->assertContainsOnlyInstancesOf(\SplFileInfo::class, $iterated);
        $this->assertEquals($expectedFilePaths, $iterated);
    }
}
