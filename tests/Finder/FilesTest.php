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

namespace SebastianBergmann\FileIterator\Finder;

use PHPUnit\Framework\TestCase;

/**
 * @covers \SebastianBergmann\FileIterator\Finder\Files
 *
 * @uses \SebastianBergmann\FileIterator\Filter\SubString
 */
final class FilesTest extends TestCase
{
    public function testFilesWithNonExistingPathReturnsIteratorWithoutElements(): void
    {
        $files = new Files(__DIR__ . '/NonExistingDirectory');
        $this->assertCount(0, $files);
    }

    public function testFilesReturningFileInFixtureDirectory(): void
    {
        $files = new Files(__DIR__ . '/_fixtures');
        $this->assertCount(1, $files);
    }

    public function testFilesWithMultipleDirectoriesReturningFileInFixtureDirectory(): void
    {
        $files = new Files([__DIR__ . '/_fixtures', __DIR__ . '/NonExistingDirectory']);
        $this->assertCount(1, $files);
    }

    public function testFilesWithWildcardDirectoryReturningFileInFixtureDirectory(): void
    {
        $files = new Files([__DIR__ . '/_fixtures/File*']);
        $this->assertCount(1, $files);
    }

    public function testFilesReturningFileInFixtureDirectoryWithPrefixAndSuffix(): void
    {
        $files = new Files(__DIR__ . '/_fixtures', 'dummy', '.txt');
        $this->assertCount(1, $files);
    }

    public function testFilesFilterFileWithPrefix(): void
    {
        $files = new Files(__DIR__ . '/_fixtures', 'invalid');
        $this->assertCount(0, $files);
    }

    public function testFilesFilterFileWithSuffix(): void
    {
        $files = new Files(__DIR__ . '/_fixtures', '', 'invalid');
        $this->assertCount(0, $files);
    }
}
