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
use SebastianBergmann\FileIterator\WildcardPaths;

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
        $this->paths    = $paths;
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

        foreach (new WildcardPaths($this->paths) as $path) {
            if (!\is_dir($path)) {
                continue;
            }
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
}
