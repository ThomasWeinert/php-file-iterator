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

use Iterator;
use SebastianBergmann\FileIterator\WildcardPaths;

final class ExcludePaths extends \FilterIterator
{
    /**
     * @var string|string[]
     */
    private $excludePaths;

    public function __construct(Iterator $iterator, $excludePaths = [])
    {
        parent::__construct($iterator);

        $this->excludePaths = new WildcardPaths($excludePaths);
    }

    public function accept(): bool
    {
        $file = $this->getInnerIterator()->current();

        if (!$file instanceof \SplFileInfo) {
            return false;
        }

        $filePath = $file->getRealPath();

        foreach ($this->excludePaths as $excludePath) {
            if ($excludePath === $filePath || 0 === \strpos($filePath, $excludePath . '/')) {
                return false;
            }
        }

        return true;
    }
}
