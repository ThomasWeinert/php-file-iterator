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

class WildcardPaths implements \IteratorAggregate
{
    private $paths;

    /**
     * WildcardPaths constructor.
     *
     * @param array|string $paths
     */
    public function __construct($paths)
    {
        $this->paths = \is_array($paths) ? $paths : [$paths];
    }

    public function getIterator()
    {
        $_paths = [];

        foreach ($this->paths as $path) {
            if ($locals = \glob($path, GLOB_ONLYDIR)) {
                \array_push($_paths, ...\array_map('\realpath', $locals));
            } else {
                $_paths[] = \realpath($path);
            }
        }

        return new \ArrayIterator(\array_filter(\array_unique($_paths)));
    }
}
