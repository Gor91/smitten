<?php
/**
 * App route configuration array.
 *
 * @author  SIXELIT <sixelit.com>
 */

return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['v1/user'],
            'extraPatterns' => [
                'login' => 'login',
                'activate' => 'activate'
            ]
        ],
    ],
];