<?php
/**
 * Filter
 *
 * @package    common
 * @subpackage components
 * @author     SIXELIT <sixelit.com>
 */

namespace common\components;

use yii\helpers\ArrayHelper;

class Filter
{
    /**
     * @param String $value
     * @return mixed
     */
    public static function cleanEnters($value)
    {
        return preg_replace('/\r|\n/', '', $value);
    }

    /**
     * @param String $value
     * @return mixed
     */
    public static function cleanInnerSpaces($value)
    {
        return preg_replace('/\s+/', ' ', $value);
    }

    /**
     * @param String $value
     * @return mixed
     */
    public static function cleanAllInnerSpaces($value)
    {
        return preg_replace('/\s+/', '', $value);
    }

    /**
     * @param $text
     * @return mixed
     */
    public static function strToLower($text)
    {
        return mb_strtolower($text, 'UTF-8');
    }

    /**
     * @param $nickname
     * @return mixed
     */
    public static function removeAtFromNickname($nickname)
    {
        return ltrim($nickname,'@');
    }

    /**
     * @param $text
     * @return mixed
     */
    public static function removeHashTag($text)
    {
        return ltrim($text, '#');
    }

    /**
     * @param $text
     * @return array
     */
    public static function getNicknamesFromText($text)
    {
        if (preg_match_all('!@(.+)(?:\s|$)!U', $text, $matches)) {
            return ArrayHelper::getValue($matches, 1, []);
        }

        return [];
    }

    /**
     * @param $criteria
     * @param bool $cleanAllSpaces
     * @return string
     */
    public static function cleanText($criteria, $cleanAllSpaces = false)
    {
        $text = self::cleanInnerSpaces(self::cleanEnters(strip_tags(trim($criteria))));

        if ($cleanAllSpaces) {
            return self::cleanAllInnerSpaces($text);
        }

        return $text;
    }

    /**
     * @param $text
     * @return mixed
     */
    public static function humanize($text)
    {
        return str_ireplace('_', ' ', ucfirst(mb_strtolower($text)));
    }
}