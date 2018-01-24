<?php
/**
 * App logs configuration array.
 *
 * @author SIXELIT <sixelit.com>
 */

return [
    'targets' => [
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error'],
            'categories' => ['app'],
            'logFile' => '@frontend/runtime/logs/error/general.log',
            'maxFileSize' => 10240 //10mb
        ],
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['warning'],
            'categories' => ['app'],
            'logFile' => '@frontend/runtime/logs/warning/general.log',
            'logVars' => [],
            'maxFileSize' => 10240 //10mb
        ],
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['trace', 'info'],
            'categories' => ['app'],
            'logFile' => '@frontend/runtime/logs/info/general.log',
            'logVars' => [],
            'maxFileSize' => 10240//10mb
        ]
    ]
];