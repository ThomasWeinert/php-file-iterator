<?php

namespace SebastianBergmann\FileIterator\Filter;

use PHPUnit\Framework\TestCase;

class SubStringTest extends TestCase
{

    /**
     * @covers \SebastianBergmann\FileIterator\Filter\SubString
     */
    public function testMatchingSuffix(): void
    {
        $filter = new SubString(
            $this->getFilesFixture(['ExampleTest.php']),
            'Test.php'
        );
        $this->assertCount(1, $filter);
    }

    /**
     * @covers \SebastianBergmann\FileIterator\Filter\SubString
     */
    public function testMatchingWithEmptySuffix(): void
    {
        $filter = new SubString(
            $this->getFilesFixture(['ExampleTest.php']),
            ''
        );
        $this->assertCount(1, $filter);
    }

    /**
     * @covers \SebastianBergmann\FileIterator\Filter\SubString
     */
    public function testNonMatchingSuffix(): void
    {
        $filter = new SubString(
            $this->getFilesFixture(['ExampleTest.php']),
            '_test.php'
        );
        $this->assertCount(0, $filter);
    }

    /**
     * @covers \SebastianBergmann\FileIterator\Filter\SubString
     */
    public function testMatchingWithMultipleSuffixes(): void
    {

        $filter = new SubString(
            $this->getFilesFixture(['ExampleTest.php', 'example_test.php']),
            ['Test.php', '_test.php']
        );
        $this->assertCount(2, $filter);
    }

    /**
     * @covers \SebastianBergmann\FileIterator\Filter\SubString
     */
    public function testMatchingPrefix(): void
    {
        $filter = new SubString(
            $this->getFilesFixture(['ExampleTest.php']),
            'Example',
            SubString::PREFIX
        );
        $this->assertCount(1, $filter);
    }

    /**
     * @covers \SebastianBergmann\FileIterator\Filter\SubString
     */
    public function testMatchingWithEmptyPrefix(): void
    {
        $filter = new SubString(
            $this->getFilesFixture(['ExampleTest.php']),
            '',
            SubString::PREFIX
        );
        $this->assertCount(1, $filter);
    }

    /**
     * @covers \SebastianBergmann\FileIterator\Filter\SubString
     */
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
