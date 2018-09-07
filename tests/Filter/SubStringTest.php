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
 * @covers \SebastianBergmann\FileIterator\Filter\SubString
 */
final class SubStringTest extends TestCase
{

    public function testMatchingSuffix(): void
    {
        $filter = new SubString(
            $this->getFilesFixture(['ExampleTest.php']),
            'Test.php'
        );
        $this->assertCount(1, $filter);
    }

    public function testMatchingWithEmptySuffix(): void
    {
        $filter = new SubString(
            $this->getFilesFixture(['ExampleTest.php']),
            ''
        );
        $this->assertCount(1, $filter);
    }

    public function testNonMatchingSuffix(): void
    {
        $filter = new SubString(
            $this->getFilesFixture(['ExampleTest.php']),
            '_test.php'
        );
        $this->assertCount(0, $filter);
    }

    public function testMatchingWithMultipleSuffixes(): void
    {
        $filter = new SubString(
            $this->getFilesFixture(['ExampleTest.php', 'example_test.php']),
            ['Test.php', '_test.php']
        );
        $this->assertCount(2, $filter);
    }

    public function testMatchingPrefix(): void
    {
        $filter = new SubString(
            $this->getFilesFixture(['ExampleTest.php']),
            'Example',
            SubString::PREFIX
        );
        $this->assertCount(1, $filter);
    }

    public function testMatchingWithEmptyPrefix(): void
    {
        $filter = new SubString(
            $this->getFilesFixture(['ExampleTest.php']),
            '',
            SubString::PREFIX
        );
        $this->assertCount(1, $filter);
    }

    public function testNonMatchingPrefix(): void
    {
        $filter = new SubString(
            $this->getFilesFixture(['ExampleTest.php']),
            'NonMatching',
            SubString::PREFIX
        );
        $this->assertCount(0, $filter);
    }

    private function getFilesFixture(array $fileNames): \ArrayIterator
    {
        $files = [];
        foreach ($fileNames as $fileName) {
          $files[] = $stub = $this->createMock(\SplFileInfo::class);
          $stub
              ->expects($this->any())
              ->method('getFilename')
              ->willReturn($fileName);
        }
        return new \ArrayIterator($files);
    }
}
