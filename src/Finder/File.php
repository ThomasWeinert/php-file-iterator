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

final class File implements \IteratorAggregate
{
    /**
     * @var string
     */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getIterator(): \ArrayIterator
    {
        if (!\file_exists($this->path)) {
            return new \ArrayIterator();
        }

        return new \ArrayIterator([
            new \SplFileInfo($this->path),
        ]);
    }
}
