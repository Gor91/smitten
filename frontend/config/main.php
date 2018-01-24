<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'smite-api',
    'name'=>'Smite',
    'version'=>'1.0.0',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' =>require(__DIR__.'/general/module.php'),
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableSession' => false,
            'loginUrl' => null
        ],
        'urlManager' =>require(__DIR__.'/general/router.php'),
        'response' =>require(__DIR__.'/general/response.php'),
        'request' =>require(__DIR__.'/general/request.php'),
        'log' =>require(__DIR__.'/general/log.php'),
//        'cache'=>[
//            'class'=>'yii\caching\DbCache',
//            'cacheTable'=>'cache'
//        ],
//        'errorHandler' => [
//            'errorAction' => 'site/error'
//        ],
    ],
    'params' => $params
];
