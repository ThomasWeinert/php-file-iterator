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

namespace SebastianBergmann\FileIterator;

use PHPUnit\Framework\TestCase;

/**
 * @covers \SebastianBergmann\FileIterator\WildcardPaths
 */
final class WildcardPathsTest extends TestCase
{
    public function testExpandToCurrentDirectory(): void
    {
        $paths = new WildcardPaths(basename(__DIR__).'/F*');
        $this->assertEquals(
            [\realpath(__DIR__.'/Filter'), \realpath(__DIR__.'/Finder')],
            \iterator_to_array($paths)
        );
    }
}
