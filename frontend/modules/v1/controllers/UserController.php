<?php
/**
 * UserController
 *
 * @apiDefine User User
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

class UserController extends RestController
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
                ],
                'except' => ['create', 'login']
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'activate' => ['POST'],
                    'login' => ['POST'],
                    'change_profile'=>['POST']
                ],
            ],
        ], parent::behaviors());
    }

    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass = 'frontend\modules\v1\models\User';

    /**
     * @var string|array the configuration for creating the serializer that formats the response data.
     */
    public $serializer = [
        'class' => 'frontend\components\RestSerializer',
        'collectionEnvelope' => 'users'
    ];

    /**
     * @return array
     */
    public function actions()
    {
        $actions = [];

        $actions['create'] = [
            'class' => 'frontend\modules\v1\controllers\userActions\CreateAction',
            'modelClass' => $this->modelClass
        ];

        $actions['activate'] = [
            'class' => 'frontend\modules\v1\controllers\userActions\ActivateAction',
            'modelClass' => $this->modelClass
        ];

        $actions['login'] = [
            'class' => 'frontend\modules\v1\controllers\userActions\LoginAction',
            'modelClass' => $this->modelClass
        ];

        $actions['change_profile'] = [
            'class' => 'frontend\modules\v1\controllers\userActions\ChangeProfileAction',
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}