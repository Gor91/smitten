<?php
/**
 * FriendController
 *
 * @apiDefine Friend Friend
 *
 * @package    frontend\modules\v1
 * @subpackage controllers
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\controllers;

use frontend\controllers\RestController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class FriendController extends RestController
{
    /**
     * Allows two types of authorization.
     *   1.Query param authorization
     *   2.Oauth2 authorization(bearer authorization).
     *
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge([
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    HttpBearerAuth::className(),
                    QueryParamAuth::className()
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'send' => ['POST'],
                    'apply' => ['PUT'],
                    'pending'=>['GET']
                ]
            ]
        ], parent::behaviors());
    }

    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass = 'frontend\modules\v1\models\Friends';

    /**
     * @var string|array the configuration for creating the serializer that formats the response data.
     */
    public $serializer = [
        'class' => 'frontend\components\RestSerializer',
        'collectionEnvelope' => 'result'
    ];

    public function actions()
    {
        $actions = [];

        $actions['index'] = [
            'class' => 'frontend\modules\v1\controllers\friendActions\IndexAction',
            'modelClass' => $this->modelClass
        ];

        $actions['send'] = [
            'class' => 'frontend\modules\v1\controllers\friendActions\SendAction',
            'modelClass' => $this->modelClass
        ];

        $actions['apply'] = [
            'class' => 'frontend\modules\v1\controllers\friendActions\ApplyAction',
            'modelClass' => $this->modelClass
        ];

        $actions['delete'] = [
            'class' => 'frontend\modules\v1\controllers\friendActions\DeleteAction',
            'modelClass' => $this->modelClass
        ];

        $actions['pending'] = [
            'class' => 'frontend\modules\v1\controllers\friendActions\PendingAction',
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}