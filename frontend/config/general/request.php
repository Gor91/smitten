<?php
/**
 * App request configuration array.
 *
 * @author   SIXELIT <sixelit.com>
 */

return [
    'class' => 'yii\web\Request',
    'enableCookieValidation' => false,
    'parsers' => [
        'application/json' => 'yii\web\JsonParser'
    ]
];