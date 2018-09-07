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

final class SubString extends \FilterIterator
{
    public const PREFIX = 0;
    public const SUFFIX = 1;

    private $subStrings;
    private $type;

    /**
     * @param \Iterator    $iterator
     * @param array|string $subStrings
     * @param int          $type
     */
    public function __construct(\Iterator $iterator, $subStrings = '', $type = self::SUFFIX)
    {
        parent::__construct($iterator);

        if (\is_string($subStrings)) {
            if ($subStrings !== '') {
                $subStrings = [$subStrings];
            } else {
                $subStrings = [];
            }
        }
        $this->subStrings = $subStrings;
        $this->type       = $type;
    }

    /**
     * Accepts file if the name starts or end with with the string (depending on type)
     *
     * @return bool
     */
    public function accept(): bool
    {
        if (empty($this->subStrings)) {
            return true;
        }
        $current  = $this->getInnerIterator()->current();
        $filename = $current->getFilename();

        foreach ($this->subStrings as $string) {
            if (
                ($this->type === self::PREFIX && \strpos($filename, $string) === 0) ||
                ($this->type === self::SUFFIX &&
                 \substr($filename, -1 * \strlen($string)) === $string)
            ) {
                return true;
            }
        }

        return false;
    }
}
