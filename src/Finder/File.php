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

final class File implements \Iterator
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    private $hasIterated = false;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function current(): \SplFileInfo
    {
        return new \SplFileInfo($this->path);
    }

    public function next(): void
    {
        $this->hasIterated = true;
    }

    public function key()
    {
        return $this->path;
    }

    public function valid(): bool
    {
        return !$this->hasIterated && \file_exists($this->path);
    }

    public function rewind(): void
    {
        $this->hasIterated = false;
    }
}
