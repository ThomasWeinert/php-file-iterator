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

final class FileTest extends TestCase
{
    public function testIterateWhenFileDoesNotExist(): void
    {
        $path = __DIR__ . '/NonExistentFile.php';

        $finder = new File($path);

        $iterated = \iterator_to_array($finder);

        $this->assertCount(0, $iterated);
    }

    public function testIterateWhenFileExists(): void
    {
        $path = __FILE__;

        $finder = new File($path);

        $iterated = \iterator_to_array($finder);

        $this->assertCount(1, $iterated);
        $this->assertContainsOnlyInstancesOf(\SplFileInfo::class, $iterated);

        $file = array_shift($iterated);

        $this->assertSame($path, $file->getRealPath());
    }
}
