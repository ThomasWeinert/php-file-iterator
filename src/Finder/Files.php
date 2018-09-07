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

use SebastianBergmann\FileIterator\Filter\SubString as SubStringFilter;

final class Files implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $paths;
    /**
     * @var array|string
     */
    private $prefixes;
    /**
     * @var array|string
     */
    private $suffixes;

    /**
     * @param array|string $paths
     * @param array|string $prefixes
     * @param array|string $suffixes
     */
    public function __construct(
        $paths,
        $prefixes = '',
        $suffixes = ''
    ) {
        if (\is_string($paths)) {
            $paths = [$paths];
        }
        $this->paths    = $this->getPathsAfterResolvingWildcards($paths);
        $this->prefixes = $prefixes;
        $this->suffixes = $suffixes;
    }

    public function getIterator(): \Iterator
    {
        $filteredFiles = new SubStringFilter(
            new SubStringFilter(
                $files = new \AppendIterator(),
                $this->suffixes,
                SubStringFilter::SUFFIX
            ),
            $this->prefixes,
            SubStringFilter::PREFIX
        );

        foreach ($this->paths as $path) {
            $files->append(
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator(
                        $path,
                        \RecursiveDirectoryIterator::SKIP_DOTS
                    )
                )
            );
        }

        return $filteredFiles;
    }

    /**
     * Expand paths with wildcards into an array of existing paths
     *
     * @param array $paths
     *
     * @return array
     */
    private function getPathsAfterResolvingWildcards(array $paths): array
    {
        $_paths = [];

        foreach ($paths as $path) {
            if ($locals = \glob($path, GLOB_ONLYDIR)) {
                \array_push($_paths, ...\array_map('\realpath', $locals));
            } else {
                $_paths[] = \realpath($path);
            }
        }

        return \array_filter(\array_unique($_paths));
    }
}
