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
                'activate' => 'activate',
                'profile' => 'change_profile'
            ]
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['v1/friend'],
            'extraPatterns' => [
                'POST <id:\d+>' => 'send',
                'PUT <id:\d+>' => 'apply',
                'pending' => 'pending'
            ]
        ]
    ]
];