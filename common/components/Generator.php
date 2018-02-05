<?php
/**
 * Generator
 *
 * @package    common
 * @subpackage components
 * @author     SIXELIT <sixelit.com>
 */

namespace common\components;

use InvalidArgumentException;

class Generator
{
    /**
     * @param $min
     * @param $max
     * @return int|null
     */
    public static function randomInt($min, $max)
    {
        if (!is_int($min) || !is_int($max) || $min < 0 || $max < 0) {
            throw new InvalidArgumentException('tripleInteger function only accepts absolute integers.');
        }

        if ($min > $max) {
            $max = $min + $max;
            $min = $max - $min;
            $max = $max - $min;
        }

        $arrayList = range($min, $max);

        $int = rand($arrayList[0], $arrayList[count($arrayList) - 1]);

        return $int;
    }
}