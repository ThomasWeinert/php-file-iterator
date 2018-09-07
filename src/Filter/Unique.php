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

final class Unique extends \FilterIterator
{
    /**
     * @var string[]
     */
    private $filePaths = [];

    public function accept(): bool
    {
        $file = $this->getInnerIterator()->current();

        if (!$file instanceof \SplFileInfo) {
            return false;
        }

        $filePath = $file->getRealPath();

        if (\in_array($filePath, $this->filePaths, true)) {
            return false;
        }

        $this->filePaths[] = $filePath;

        return true;
    }
}
